<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/mail/logs', \OZiTAG\Tager\Backend\Mail\Controllers\AdminController::class . '@logs');
    Route::get('/mail/templates', \OZiTAG\Tager\Backend\Mail\Controllers\AdminController::class . '@templates');
    Route::put('/mail/templates', \OZiTAG\Tager\Backend\Mail\Controllers\AdminController::class . '@update');
});
