<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');
Route::get('/backpropagation', 'BackpropagationController@index')->name('backpropagation');
Route::post('/traindata', 'BackpropagationController@traindata')->name('traindata');
Route::get('/prediction', 'PredictionController@index')->name('prediction');
Route::post('/process', 'PredictionController@process')->name('process');
Route::resource('/data', 'DataController');
Route::resource('/testing', 'TestingController');
