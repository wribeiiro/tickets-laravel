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

/*Route::get('/', function () {
    return view('welcome');
});*/
//Route::resource('/', HomeController::class);

Route::any('login',         'AuthController@login')->name('login');
Route::get('logoff',         'AuthController@logoff')->name('logoff');
Route::get('auth',           'AuthController@auth')->name('auth');
Route::get('getChartTickets',     'TicketController@getChartTickets')->name('getChartTickets');
Route::get('getCardTicket', 'TicketController@getCardTicket')->name('getCardTicket');
Route::get('getTickets', 'TicketController@getTickets')->name('getTickets');

Route::group(['middleware' => 'sessionUserNoExists'], function () {
    Route::get('home',  'HomeController@index')->name('home');
    Route::get('tickets',  'TicketController@index')->name('tickets');
});

Route::group(['middleware' => 'sessionUserExists'], function () {
    Route::get('/',     'AuthController@auth');
});
