<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LabelController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/label/{product}/{count}', [LabelController::class, 'show'])
     ->name('label.show');