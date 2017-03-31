<?php


use Illuminate\Support\Facades\Input;

Route::group(['middleware' => ['web']], function () {
    //Route::auth();
    Route::get('/', 'HomeController@index');
    //前台用户
    Route::any('home/login', 'Auth\AuthController@login');
    Route::get('home/logout', 'Auth\AuthController@logout');
    Route::any('home/register', 'Auth\AuthController@register');
    Route::get('/home', 'HomeController@index');

    Route::get('email','EmailController@eema');
    Route::get('mail/sendReminderEmail/{id}','MailController@sendReminderEmail');
    //后台管理员
    Route::any('admin/login', 'Admin\AuthController@login');
    Route::any('admin/logout', 'Admin\AuthController@logout');
    Route::any('admin/register', 'Admin\AuthController@register');
    Route::get('/admin', 'AdminController@index');
    Route::resource('admin/article', 'Admin\ArticleController');
    Route::resource('admin/admin', 'Admin\AdminController');
    Route::resource('admin/role', 'Admin\RoleController');
    Route::resource('admin/permission', 'Admin\PermissionController');
    Route::any('admin/Isthere', 'Admin\AuthController@Isthere');
});
