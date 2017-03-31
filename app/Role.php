<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //表名
    protected $table = 'roles';

    //指定主键
    protected $primaryKey = 'id';

}
