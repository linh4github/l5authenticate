<?php

Route::group(
    [
        'middleware' => ['web'],
        'namespace'  => 'Modules\Authenticate\Http\Controllers',
        'prefix'     => 'auth'
    ], function () {

    Route::get('register',[
        'as'   => 'auth.register.index',
        'uses' => 'RegisterController@index'
    ]);

    Route::post('register',[
        'as'   => 'auth.register',
        'uses' => 'RegisterController@register'
    ]);

    Route::get('activate/{id}/{code}', [
        'as'   => 'auth.activate-link',
        'uses' => 'RegisterController@activateLink'
    ])->where(['id' => '[0-9]+']);

    Route::get('login',[
        'as'   => 'auth.login.get',
        function(){
            if (Session::get(config('authenticate.session'))) return redirect()->route('home');
            return view('authenticate::login');
        }
    ])->middleware(Modules\Authenticate\Http\Middlewares\CheckUserLogin::class);

    Route::post('login',[
        'as'   => 'auth.login',
        'uses' => 'LoginController@login'
    ]);

    Route::get('logout',[
        'as'   => 'auth.logout',
        'uses' => 'LoginController@logout'
    ]);

    Route::get('reminder', [
        'as'   => 'auth.reminder',
        'uses' => 'ReminderController@index'
    ]);

    Route::post('remind-password-by-email', [
        'as'   => 'auth.remind-password-by-email',
        'uses' => 'ReminderController@remindPasswordByEmail'
    ]);

    Route::get('reset-password/{id}/{code}', [
        'as'   => 'auth.reset-password',
        'uses' => 'ReminderController@resetPasswordLink'
    ])->where(['id' => '[0-9]+']);


    Route::get('test', [
        'as' => 'home',
        function(){

            return view('authenticate::test');
        }
    ]);
});

