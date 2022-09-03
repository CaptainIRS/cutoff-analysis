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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/search-by-program', function () {
    return view('search-by-program');
})->name('search-by-program');

Route::get('/search-by-institute', function () {
    return view('search-by-institute');
})->name('search-by-institute');

Route::get('/program-trends', function () {
    return view('program-trends');
})->name('program-trends');

Route::get('/institute-trends', function () {
    return view('institute-trends');
})->name('institute-trends');

Route::get('/round-trends', function () {
    return view('round-trends');
})->name('round-trends');

Route::get('/field-trends', function () {
    return view('field-trends');
})->name('field-trends');
