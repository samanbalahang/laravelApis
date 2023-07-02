<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TheTestController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PreRegisterController;
use App\Http\Controllers\PreRegisterColleagueController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\TuitionController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\TiketsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::resource('/test',                    TheTestController::class);
Route::get('/{uri?}',                       [PostController::class,"postUri"])->name('post.uri');
Route::resource('/post',                    PostController::class);
Route::post('/user/send-phone',             [UserProfileController::class,"getPhone"])->name('user.getPhone');
Route::get('/news',                         [PostController::class,"index"])->name('post.news');
Route::get('/article',                      [PostController::class,"index"])->name('post.article');
Route::get('/news/{uri?}',                  [PostController::class,"postUri"])->name('post.uri');
Route::get('/article/{uri?}',               [PostController::class,"postUri"])->name('post.uri');
Route::resource('/preRegister',             PreRegisterController::class);
Route::resource('/preRegcolleag',           PreRegisterColleagueController::class);
Route::resource('/media',                   MediaController::class);
Route::resource('/collection',              CollectionController::class);
Route::resource('/gallery',                 GalleryController::class);
Route::resource('/class',                   ClassesController::class);
// شهریه
Route::resource('/tuition',                 TuitionController::class);
Route::resource('/bill',                    BillController::class);
Route::resource('/factor',                  FactorController::class);
Route::resource('/tiket',                   TiketsController::class);




