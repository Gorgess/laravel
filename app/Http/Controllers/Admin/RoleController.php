<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use App\PermissionRole;
use App\RbacControl;
use App\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
class RoleController extends Controller
{
    protected $_permissionDb = '';
    public function __construct()
    {
        $this->_permissionDb = new Permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Roles = DB::table('roles')->orderby('updated_at','desc')->paginate(5);
        return view('admin/role/index',['Roles' => $Roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/role/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'name' =>  'required|unique:roles|max:255',
            'display_name'  =>  'required',
            'description'  =>  'required',
        ]);
        $PermissionDb = new Permission();
        $RbacControl = new RbacControl();
        $Role = new Role;

        $Role->name = $request->get('name');
        $Role->display_name = $request->get('display_name');
        $Role->description = $request->get('description');

        $PermData = $PermissionDb->get(['id']);
        if($Role->save()){
            foreach($PermData as $Pe){
                $RbacControl->insert([
                    'permission_id' => $Pe->id,
                    'insert' => 0,
                    'update' => 0,
                    'delete' => 0,
                    'updated_at' => date('Y-m-d H:i:s',time()),
                    'created_at' => date('Y-m-d H:i:s',time()),
                    'role_id' => $Role->id,
                ]);
            }
            return redirect('admin/role');
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
        $permRoleDb = new PermissionRole();
        $permRole = $permRoleDb->GetPermsissionRoleList($id);
        $perms = $this->_permissionDb->GetPermissionList($id);
        return view('admin/role/edit',[
            'perms' => $perms,
            'role_id' => $id,
            'permRole' => $permRole
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
        $permissionRoleDb = new PermissionRole;
        $permissionDb = new Permission;
        $RbacControl = new RbacControl();

        $permData = array();
        $RbacArr = array();
        $UserId = Auth::guard('admin')->user()->id;
        if($request->get('rbac')) {
            foreach ($request->get('rbac') as $rbac) {
                $imrbac = array();
                $imrbac = explode(',', $rbac);
                $NewRbac['permission_id'] = $imrbac[0];


                $NewRbac['type'] = $imrbac[2];
                $RbacControl->where(['role_id' => $id, 'permission_id' => $imrbac[0]])->update([
                    "$imrbac[2]" => $imrbac[1],
                    'updated_at' => date('Y-m-d H:i:s', time())
                ]);
            }
        }

        $permissionRoleDb->where('role_id', '=', $id)->delete();
        foreach ($request->get('perm') as $perm) {
            $Newperm['permission_id'] = (int)$perm;
            $Newperm['role_id'] = (int)$id;
            $Newperm['is_parent'] = 0;

            $Parent = $permissionDb->IsParentinfo($perm);
            if ($Parent != 0) {
                $Newperm['is_parent'] = (int)$Parent;
            }
            $permData[] = $Newperm;
        }
        //数组去重
        $functions = new \functions();

        $permDatas = $functions->remove_array($permData);
        $permissionRoleDb->insert($permDatas);


        return redirect()->back();

        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('roles')->where('id','=',$id)->delete();
        return redirect()->back();
    }
}

