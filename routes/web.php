<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\User\LoginController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [LoginController::class,'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login.post');


//Admin
Route::group(['middleware' => ['auth', 'admin']], function () {
    // Posts 
    Route::post('/posts/store', [PublicacionController::class, 'create'])->name('posts.store');
});


Route::group(["middelare"=>["auth"]],function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/posts/{post}', [PublicacionController::class, 'show'])->name('posts.show');
    Route::post("post/report",[PublicacionController::class,"report"])->name("post.report");
});
