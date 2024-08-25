<?php

use Homeful\KwYCCheck\Http\Controllers\AttachLeadMediaController;
use Homeful\KwYCCheck\Http\Controllers\ProcessLeadController;
use Illuminate\Support\Facades\Route;

Route::post('process-lead', ProcessLeadController::class)
    ->prefix('api')
    ->middleware('api')
    ->name('process-lead');

Route::post('attach-media/{lead}', AttachLeadMediaController::class)
    ->prefix('api')
    ->middleware('api')
    ->name('attach-media');
