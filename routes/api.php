<?php

use App\Modules\Advertisements\Controllers\AdvertisementRpcController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->post(
    '/personal-advertisements',
    [AdvertisementRpcController::class, 'getAdvertisements']
);

Route::middleware('auth:sanctum')->post(
    '/save-advertisement',
    [AdvertisementRpcController::class, 'saveAdvertisement']
);

// Advertisements
Route::post('/get-advertisements', [AdvertisementRpcController::class, 'getAdvertisements']);
Route::get('/get-categories', [AdvertisementRpcController::class, 'getJobCategories']);
