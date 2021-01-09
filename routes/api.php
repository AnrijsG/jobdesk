<?php

use App\Http\Controllers\AuthController;
use App\Modules\Advertisements\Controllers\AdvertisementRpcController;
use App\Modules\FileUpload\Controllers\FileController;
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

Route::middleware('auth:sanctum')->post(
    '/delete-advertisement',
    [AdvertisementRpcController::class, 'deleteAdvertisement']
);

// Advertisements
Route::post('/get-advertisements', [AdvertisementRpcController::class, 'getAdvertisements']);
Route::get('/get-categories', [AdvertisementRpcController::class, 'getJobCategories']);


// CVs
Route::middleware('auth:sanctum')->post(
    '/upload-cv',
    [FileController::class, 'saveCv']
);

Route::middleware('auth:sanctum')->get(
    '/get-personal-cv-url',
    [FileController::class, 'getPersonalCvDownloadUrl']
);

Route::middleware('auth:sanctum')->get(
    '/get-personal-cv-modified-at',
    [FileController::class, 'getPersonalCvModifiedAt']
);

Route::middleware('auth:sanctum')->post(
    '/delete-cv',
    [FileController::class, 'deleteCv']
);

// Company logos
Route::middleware('auth:sanctum')->post(
    '/upload-logo',
    [FileController::class, 'saveLogo']
);

Route::middleware('auth:sanctum')->post(
    '/delete-logo',
    [FileController::class, 'deleteLogo']
);

// Job application submissions
Route::middleware('auth:sanctum')->post(
    '/submit-application',
    [AdvertisementRpcController::class, 'submitApplication']
);

Route::middleware('auth:sanctum')->post(
    '/get-appliable-advertisements',
    [AdvertisementRpcController::class, 'getAppliableAdvertisements']
);

Route::middleware('auth:sanctum')->post(
    '/get-applicants',
    [AdvertisementRpcController::class, 'getApplicants']
);

// Environment stuff
Route::middleware('auth:sanctum')->post(
    '/set-company-website',
    [AuthController::class, 'setCompanyWebsite']
);
