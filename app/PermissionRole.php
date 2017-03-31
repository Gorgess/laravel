<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    //表名
    protected $table = 'permission_role';


    public function GetPermsissionRoleList($id)
    {
        $PeRole = $this->where('role_id','=',$id)->get();
        $prData = array();
        foreach($PeRole as $pr) {
            $prData[] = $pr->permission_id;
        }
        return $prData;
    }

}
