<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;
use Storage;
use App\User;
use App\Jobs\SendReminderEmail;
class MailController extends Controller
{
    //其他方法
    //发送提醒邮件
    public function sendReminderEmail(Request $request,$id){
        $user = User::findOrFail($id);
        $this->dispatch(new SendReminderEmail($user));
    }
}