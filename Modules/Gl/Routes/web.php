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
*/

use Illuminate\Support\Facades\Route;

Route::prefix('gl')->group(function() {
    Route::get('/', 'GlController@index');

/**GL Reports */
Route::get('/gl_reports',function(){
    return view('gl::gl_report');
});
});
