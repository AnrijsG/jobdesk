<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobDeskController;
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

Route::get('/{any}', [JobDeskController::class, 'index'])->where('any', '.*');

// AUTH
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'authenticate']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth/get-user', [AuthController::class, 'getUser']);
Route::post('/auth/get-environment-users', [AuthController::class, 'getEnvironmentUsers']);
Route::post('/auth/reset-registration-hash', [AuthController::class, 'resetRegistrationHash']);
Route::post('/auth/get-registration-hash', [AuthController::class, 'getRegistrationHash']);
Route::post('/auth/delete-registration-hash', [AuthController::class, 'deleteRegistrationHash']);
