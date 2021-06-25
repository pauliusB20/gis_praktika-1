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
Route::get('/', 'MDataCollector@getMuzData')->name('mapdata');

//For testing and tutorials routes
// Route::get('/', function () {
//     return view('apppage');
// });

Route::get('/app', function () {
    return view('apppage');
});
