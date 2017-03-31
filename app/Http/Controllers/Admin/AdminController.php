<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\RoleUser;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Admin = DB::table('admins')->orderby('updated_at','desc')->paginate(5);
        return view('admin/admin/index',['Admins' => $Admin]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //用户关联的权限
        $RoleUserDb = new RoleUser;
        $UserRole = $RoleUserDb->GetUserRole($id);
        //所有权限
        $RoleDb = new Role;
        $RoleData = $RoleDb->get();

        return view('admin/admin/edit',[
            'Userroles' => $UserRole,
            'Roles' => $RoleData,
            'admin_id' => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //数据验证
        $validator = $this->validateRegister($request->input());
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        //数据重组
        $Data = $request->input();
        $roleArr = array();
        foreach($Data['role_id'] as $role) {
            $Newrole['role_id'] = $role;
            $Newrole['user_id'] = $id;
            $roleArr[] = $Newrole;
        }
        DB::table('role_user')->where('user_id', '=', $id)->delete();
        if(DB::table('role_user')->insert($roleArr)){
            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function validateRegister(array $data)
    {
        return Validator::make($data, [
            'role_id' => 'required'
        ], [
            'required' => ':attribute 为必选项',

        ], [
            'role_id' => '角色',

        ]);
    }
}
