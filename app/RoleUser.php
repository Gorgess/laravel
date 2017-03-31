<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    //表名
    protected $table = 'role_user';

    public function GetUserRole($id) {
        $roles = $this->where(['user_id'=>$id])->get();
        $newRole = array();
        foreach($roles as $role) {
            $newRole[] = $role->role_id;
        }
        return $newRole;
    }
}
