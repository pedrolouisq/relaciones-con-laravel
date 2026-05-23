<?php

use App\Http\Controllers\RelationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('relations')->group(function () {
    Route::get('setup', [RelationController::class, 'setup']);
    Route::get('user/{user}', [RelationController::class, 'showUser']);
    Route::get('post/{post}', [RelationController::class, 'showPost']);
    Route::get('video/{video}', [RelationController::class, 'showVideo']);
    Route::get('role/{role}', [RelationController::class, 'showRole']);
});
