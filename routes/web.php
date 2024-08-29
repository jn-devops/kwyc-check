<?php

use Homeful\KwYCCheck\Http\Controllers\CreateLeadContactController;
use Illuminate\Support\Facades\Route;

Route::post('create-lead-contact/{lead}', CreateLeadContactController::class)
    ->middleware('web')
    ->name('create-lead-contact');

