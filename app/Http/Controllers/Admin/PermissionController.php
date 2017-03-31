<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use App\RbacControl;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Validator;
class permissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_Permission = '';
    public function __construct()
    {
        $this->_Permission = new Permission();
    }

    public function index()
    {
        $PeDb = new Permission;
        $NewPe = array();
        $perms = $PeDb->where('parent_id','=',0)->orderBy('sort','asc')->get();
        $Newperms = array();
        foreach($perms as $perm)
        {
            $permss['perm'] = $perm;
            $permss['perms'] = $PeDb->where('parent_id','=',$perm['id'])->get();
            $Newperms[] = $permss;
        }
        return view('admin/permission/index',['Newperms' => $Newperms]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $perms = $this->_Permission->where('parent_id','=',0)->get();
        return view('admin/permission/create',['perms'=>$perms]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //数据验证
        $validator = $this->validateRegister($request->input());
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $parent_id = isset($request->parent_id)?$request->parent_id:0;
        $PermissionDb = new Permission;
        $PermissionDb->name = $request->name;
        $PermissionDb->display_name = $request->display_name;
        $PermissionDb->description = $request->description;
        $PermissionDb->sort = $request->sort;
        $PermissionDb->parent_id = $parent_id;
        if($PermissionDb->save()){

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
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
        $perms = $this->_Permission->where('parent_id','=',0)->get();
        $permsData = $this->_Permission->Getpermissioninfo($id);

        return view('admin/permission/edit',['perms'=>$perms ,'permsData'=>$permsData]);
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

        $PermissionDb = Permission::findOrFail($id);
        $PermissionDb->name = $request->name;
        $PermissionDb->display_name = $request->display_name;
        $PermissionDb->description = $request->description;
        $PermissionDb->sort = $request->sort;
        $PermissionDb->parent_id = isset($request->parent_id)?$request->parent_id:0;
        if($PermissionDb->save()){

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
        $perdata = Permission::find($id);
        $parent = DB::table('permissions')->where('parent_id','=',$perdata->parent_id)->count();

        if(Permission::find($id)->delete()){
            if($parent == 1) {
                Permission::find($perdata->parent_id)->delete();
            }
            return redirect('admin/permission');
        } else {
            return redirect()->back()->withInput()->withErrors('删除失败！');
        }
    }
    protected function validateRegister($data)
    {
        return Validator::make($data, [
            'name' => 'required',
        ], [
            'required' => ':attribute 为必选项',
        ], [
            'name' => '权限名',
            'dislpay_name' => '路由',
        ]);
    }
}
