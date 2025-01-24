<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('register',[AuthController::class, 'register'])->name('register');
Route::post('login',[AuthController::class, 'login'])->name('login');




// Route::middleware('auth:api')->get('/user/{id}', [PostController::class, 'getUserById']);
Route::get('/tasks',[TaskController::class, 'index'])->middleware('auth:api');
Route::post('/tasks',[TaskController::class, 'store'])->middleware('auth:api');
Route::get('/tasks/{id}',[TaskController::class, 'show'])->middleware('auth:api');
Route::put('/tasks/{id}',[TaskController::class, 'update'])->middleware('auth:api');
Route::delete('/tasks/{id}',[TaskController::class, 'destroy'])->middleware('auth:api');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');