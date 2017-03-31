<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use App\Http\Requests;
use Auth;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    //登录页面
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = $this->validateLogin($request->input());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            if (Auth::guard('web')->attempt($this->validateUser($request->input()))) {
                $request->session()->put('email', $request->email);
                return Redirect::to('home')->with('success', '登录成功！');
            }else {
                return back()->with('error', '账号或密码错误')->withInput();
            }
        }
        return view('auth.login');
    }
    //登录页面验证
    protected function validateLogin(array $data)
    {
        return Validator::make($data, [
            'email' => 'required',
            'password' => 'required',
        ], [
            'required' => ':attribute 为必填项',
            'min' => ':attribute 长度不符合要求'
        ], [
            'email' => '邮箱',
            'password' => '密码'
        ]);
    }
    //验证用户字段
    protected function validateUser(array $data)
    {
        return [
            'email' => $data['email'],
            'password' => $data['password']
        ];
    }
    //退出登录
    public function logout()
    {
        if(Auth::guard('web')->user()){
            Auth::guard('web')->logout();
        }
        return Redirect::to('home');
    }
    //注册
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = $this->validateRegister($request->input());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $user = new User();
            $user->name = $request->name;$user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->created_at = time();
            $user->updated_at = time();
            if($user->save()){
                return redirect('home/login')->with('success', '注册成功！');
            }else{
                return back()->with('error', '注册失败！')->withInput();
            }
        }
        return view('auth.register');
    }
    protected function validateRegister(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|alpha_num|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6|'
        ], [
            'required' => ':attribute 为必填项',
            'min' => ':attribute 长度不符合要求',
            'confirmed' => '两次输入的密码不一致',
            'unique' => '该邮箱已经被人占用',
            'alpha_num' => ':attribute 必须为字母或数字'
        ], [
            'name' => '昵称',
            'email' => '邮箱',
            'password' => '密码',
            'password_confirmation' => '确认密码'
        ]);
    }

}