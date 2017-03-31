<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use App\Http\Requests;
use Auth;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Jobs\SendReminderEmail;
use Mail;
class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

//    protected $redirectTo = 'admin';
//    protected $guard = 'admin';
//    protected $loginView = 'admin.login';
//    protected $registerView = 'admin.register';

    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'logout']);
    }
    //登录
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {

            $validator = $this->validateLogin($request->input());

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if (Auth::guard('admin')->attempt(['account_number'=>$request->account_number, 'password'=>$request->password])) {
                $userDb = Admin::findOrFail(Auth::guard('admin')->user()->id);
                $userDb->last_ip = $_SERVER["REMOTE_ADDR"];
                $userDb->updated_at = date('Y-m-d H:i:s',time());
                if($userDb->save()){
                    $UserId = Auth::guard('admin')->user()->id;
                    $functions = new \functions();
                    $functions->GetroleList($UserId);
                    $user = Admin::findOrFail($UserId);

                    $this->dispatch(new SendReminderEmail($user));

                    return Redirect::to('admin')->with('success', '登录成功！');
                } else {
                    return back()->with('error', '错误@@')->withInput();
                }
                     //login success, redirect to admin
            } else {
                return back()->with('error', '账号或密码错误')->withInput();
            }
        }
      
        return view('admin.login');
    }
    //登录页面验证
    protected function validateLogin(array $data)
    {
        return Validator::make($data, [
            'account_number' => 'required|alpha_num',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ], [
            'required' => ':attribute 为必填项',
            'min' => ':attribute 长度不符合要求',
            'captcha' => '验证码不正确'
        ], [
            'account_number' => '账号',
            'password' => '密码',
            'captcha' => '验证码'
        ]);
    }

    //退出登录
    public function logout()
    {
        if(Auth::guard('admin')->user()){
            Auth::guard('admin')->logout();
        }

        return Redirect::to('admin');
    }
    //注册
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = $this->validateRegister($request->input());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $user = new Admin();
            $user->name = $request->name;
            $user->account_number = $request->account_number;
            $user->password = bcrypt($request->password);
            $user->created_at = date('Y-m-d H:i:s',time());
            $user->updated_at = date('Y-m-d H:i:s',time());
            $user->last_ip = $_SERVER["REMOTE_ADDR"];
            if($user->save()){
                return redirect('admin/login')->with('success', '注册成功！');
            }else{
                return back()->with('error', '注册失败！')->withInput();
            }
        }
        return view('admin.register');
    }
    public function Isthere()
    {
        echo 2;
    }
    protected function validateRegister(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|alpha_num|max:255',
            'account_number' => 'required|alpha_num|unique:admins',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6|'
        ], [
            'required' => ':attribute 为必填项',
            'min' => ':attribute 长度不符合要求',
            'confirmed' => '两次输入的密码不一致',
            'unique' => '该账户已存在',
            'alpha_num' => ':attribute 必须为字母或数字',
            'max' => ':attribute 长度过长'
        ], [
            'name' => '昵称',
            'account_number' => '账号',
            'password' => '密码',
            'password_confirmation' => '确认密码'
        ]);
    }

}