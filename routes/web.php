<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\ReporteController;
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

Route::get('/', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login.post');


//Admin
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/posts/aprove', [PublicacionController::class, 'showListAprove'])->name('posts.aprove');
    Route::put('/post/aprove/{id}', [PublicacionController::class, 'aprove'])->name('post.aprove');
    Route::put('/post/rechaze/{id}', [PublicacionController::class, 'rechaze'])->name('post.rechaze');
});


Route::group(["middelare" => ["auth"]], function () {
    Route::post('/posts/store', [PublicacionController::class, 'create'])->name('posts.store');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/posts/{post}', [PublicacionController::class, 'show'])->name('posts.show');
    Route::post("/post/report", [ReporteController::class, "createReport"])->name("post.report");

    Route::get("/myposts",[PublicacionController::class,"myPostsView"])->name("myposts");
});
