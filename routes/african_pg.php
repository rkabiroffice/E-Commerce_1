<?php

use App\Http\Controllers\Api\V2\FlutterwaveController;
use Illuminate\Support\Facades\Route;

// Flutterwave callback route kept on the real controller class present in this project.
Route::get('/rave/callback', [FlutterwaveController::class, 'callback'])->name('flutterwave.callback');
