<?php

use App\Modules\Advertisements\Controllers\AdvertisementRpcController;
use Illuminate\Http\Request;
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
    [AdvertisementRpcController::class, 'getAdvertisementsByEnvironmentId']
);

Route::post('/get-advertisements', [AdvertisementRpcController::class, 'getAdvertisements']);
