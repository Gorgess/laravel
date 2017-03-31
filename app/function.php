<?php
/**
 * Created by PhpStorm.
 * User: 啊啊晨
 * Date: 2017/3/28
 * Time: 15:55
 */
use DB;
class functions extends \App\Http\Controllers\Controller {
    //获取用户菜单
    public function GetroleList ($uid) {
        $RoleUserDb = new \App\RoleUser();
        $PermissionRoleDb = new \App\PermissionRole();
        $permissionDb = new \App\Permission();
        $RoleData = $RoleUserDb->where('user_id','=',$uid)->get();
        $IsNull = '';
        foreach($RoleData as $role){
            $IsNull = $role;
        }
        if($IsNull) {
            foreach ($RoleData as $rd) {
                $perole[] = $PermissionRoleDb->where('role_id', '=', $rd->role_id)->get();
            }
            //分组
            $newRole = array();

            foreach ($perole as $Drow) {
                $newRow = array();
                foreach ($Drow as $Erow) {
                    $newRows['permission_id'] = $Erow->permission_id;
                    $newRows['role_id'] = $Erow->role_id;
                    $newRows['is_parent'] = $Erow->is_parent;
                    $newRow[$newRows['is_parent']][] = $newRows;
                }
                $newRole[] = $newRow;
            }

            $newPe = array();

            foreach ($newRole as $pe) {
                $newPedata = array();
                foreach ($pe as $key => $npe) {
                    $newPedatas = array();
                    $newPedatas['par'] = $permissionDb->Getpermissioninfo($key);
                    foreach ($npe as $spe) {
                        $newPedatas['data'][] = $permissionDb->Getpermissioninfo($spe['permission_id']);
                    }
                    $newPedata[] = $newPedatas;
                }
                $newPe[] = $newPedata;
            }
            return $newPe;
        }

    }
    //数组去重
    public function remove_array($arry){
        $result = array();
        foreach($arry as $k => $v){
            $has = false;
            foreach($result as $val){
                if($val['permission_id'] == $v['permission_id']){
                    $has = true;
                    break;
                }
            }
            if(!$has){
                $result[] = $v;
            }
        }
        return $result;
    }
}
