<?php

//Paytm

use App\Http\Controllers\Api\V2\PaytmController;
use App\Http\Controllers\MyFatoorahController;
use Illuminate\Support\Facades\Route;

Route::controller(PaytmController::class)->group(function () {
    Route::get('/paytm/index', 'pay');
    Route::post('/paytm/callback', 'callback')->name('paytm.callback');
});

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::controller(PaytmController::class)->group(function () {
        Route::get('/paytm_configuration', 'credentials_index')->name('paytm.index');
        Route::post('/paytm_configuration_update', 'update_credentials')->name('paytm.update_credentials');
    });
});

//Myfatoorah START
Route::get('/myfatoorah/callback', [MyFatoorahController::class,'callback'])->name('myfatoorah.callback');
