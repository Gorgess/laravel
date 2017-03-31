<?php

namespace App\Http\Middleware;

use App\RbacControl;
use Closure;
use Auth;
use DB;
class Authority
{
    protected $_Init = array();
    public function __construct()
    {
        $this->_Init = array('/admin/logout','/admin/login','/admin/register','/admin');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $RequestUrl = $_SERVER['REQUEST_URI'];
        $RbacControlDb = new RbacControl();
        if(strstr($RequestUrl,'admin')){

            if(Auth::guard('admin')->user()){
                $uid = Auth::guard('admin')->user()->id;
                $permission = DB::table('role_user as a')
                    ->leftjoin('permission_role as b','a.role_id','=','b.role_id')
                    ->leftjoin('permissions as c','b.permission_id','=','c.id')
                    ->where('a.user_id','=',$uid)
                    ->get(['c.display_name','c.id','b.role_id']);
                $bit = 0;
                $bits = 0;
                $UrlArr = explode('/',$RequestUrl);

                if(!in_array($RequestUrl,$this->_Init)){

                    foreach($permission as $pe){
                        $peArr = explode('/',$pe->display_name);
                        if(count($peArr) > 2) {
                            if ($UrlArr[1] == $peArr[1] && $UrlArr[2] == $peArr[2]) {
                                $rbac = $RbacControlDb->where([
                                    'permission_id' => $pe->id,
                                    'role_id' => $pe->role_id
                                ])->first();
                                if (count($UrlArr) == 4) {
                                    if (is_numeric($UrlArr[3])) {
                                        if ($rbac->delete == 1) {
                                            $bit = 1;
                                        } elseif ($rbac->update == 1) {
                                            $bit = 1;
                                        }
                                    } else {
                                        if ($rbac->insert == 1) {
                                            $bit = 1;
                                        }
                                    }
                                }

                                if (count($UrlArr) == 5) {
                                    if ($rbac->update == 1) {
                                        $bit = 1;
                                    }
                                }
                                if (count($UrlArr) == 3) {
                                    $bit = 1;
                                }
                                $bits++;
                            }

                        } else {
                            $bits = 0;
                        }
                    }
                        if($bit === 0 && $bits >= 1) {
                            return redirect()->guest("$UrlArr[1]/$UrlArr[2]")->withInput()->withErrors('您没有权限操作');
                        }

                        if ($bit === 0 && $bits === 0) {
                            return redirect()->guest("/admin")->withInput()->withErrors('您没有权限访问');
                        }
                    }
                }

             }
        return $next($request);
    }
}
