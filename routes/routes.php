<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Mail\Controllers\AdminController;

Route::group(['prefix' => 'admin', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/mail/logs', [AdminController::class, 'logs']);
    Route::get('/mail/templates', [AdminController::class, 'templates']);
    Route::put('/mail/templates', [AdminController::class, 'update']);
});
