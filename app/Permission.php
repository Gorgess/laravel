<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $table = 'permissions';
    //获取集合
    public function GetPermissionList($id){
        $RbacControl = new RbacControl();
        $perms = $this->where('parent_id','=',0)->get();
        $Newperms = array();
        foreach($perms as $perm)
        {
            $permss['perm'] = $perm;
            $parent = $this->where('parent_id','=',$perm['id'])->get();
            $NewMs = array();
            foreach($parent as $par) {
                $Newpe['ms'] = $par;
                $Newpe['pe'] = $RbacControl->where(['permission_id'=>$par->id,'role_id'=>$id])->get();
                $NewMs[] = $Newpe;
            }
            $permss['permt'] = $NewMs;
            $Newperms[] = $permss;
        }
        return $Newperms;
    }
    //是否顶级
    public function IsParentinfo($id) {
        $parent = $this->where('id','=',$id)->first();
        return $parent->parent_id;
    }
    //获取单个集合
    public function Getpermissioninfo($id) {
        $temp = $this->where('id','=',$id)->first();
        return $temp;
    }
}
