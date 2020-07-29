<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Mail\Controllers\AdminController;

Route::group(['prefix' => 'admin', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/mail/logs', [AdminController::class, 'logs']);
    Route::get('/mail/templates', [AdminController::class, 'templates']);
    Route::get('/mail/templates/{id}', [AdminController::class, 'view']);
    Route::put('/mail/templates/{id}', [AdminController::class, 'update']);
});
