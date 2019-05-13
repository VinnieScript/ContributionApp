<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/','mainController@index');
Route::get('/admin','mainController@admin');
Route::post('/adminlogin','mainController@adminlogin');
Route::post('/addcustomer','mainController@addcustomer');
Route::post('/fetchdetails','mainController@fetchdetails');
Route::post('/posttransaction','mainController@posttransaction');
Route::post('/loadpending','mainController@loadpending');
Route::post('/approvepost','mainController@approvepost');
Route::post('/notificationCount','mainController@notificationCount');
Route::post('/cashoutfetch','mainController@cashoutfetch');
Route::post('/withdrawal','mainController@withdrawal');
Route::post('/clientbalance','mainController@clientbalance');

