<?php

use Homeful\KwYCCheck\Http\Controllers\AttachLeadMediaController;
use Homeful\KwYCCheck\Http\Controllers\GenerateQRCodeController;
use Homeful\KwYCCheck\Http\Controllers\ProcessLeadController;
use Homeful\KwYCCheck\Http\Controllers\HypervergeController;

use Illuminate\Support\Facades\Route;

Route::post('process-lead', ProcessLeadController::class)
    ->prefix('api')
    ->middleware('api')
    ->name('process-lead');

Route::post('attach-media/{lead}', AttachLeadMediaController::class)
    ->prefix('api')
    ->middleware('api')
    ->name('attach-media');

Route::post('generate-qr', GenerateQRCodeController::class)
    ->prefix('api')
    ->middleware('api')
    ->name('generate-qr');

    Route::post('validate/id',  [HypervergeController::class, 'validate_id'])
    ->prefix('api')
    ->middleware('api')
    ->name('id validation');
    Route::post('check/liveliness',  [HypervergeController::class, 'validate_live_url'])
    ->prefix('api')
    ->middleware('api')
    ->name('liveliness validation');
    Route::post('check/liveliness/base64',  [HypervergeController::class, 'validate_live_base64'])
    ->prefix('api')
    ->middleware('api')
    ->name('liveliness validation');
    Route::post('check/facematch',  [HypervergeController::class, 'face_match'])
    ->prefix('api')
    ->middleware('api')
    ->name('face match validation');
    Route::post('check/facematch/base64',  [HypervergeController::class, 'face_match_base64'])
    ->prefix('api')
    ->middleware('api')
    ->name('face match validation');




