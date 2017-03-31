<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;
use Storage;
use App\User;
use App\Jobs\SendReminderEmail;
class EmailController extends Controller{
    public function eema(){
        Mail::raw('成功了的样子呦',function($message){
            $message->subject('邮件功能测试');
            $message->to('419422085@qq.com');      //接收方的邮箱号
        });
    }
}