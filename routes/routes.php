<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/mail/templates', \OZiTAG\Tager\Backend\Mail\Controllers\AdminTemplatesController::class . '@index');
    Route::put('/mail/templates', \OZiTAG\Tager\Backend\Mail\Controllers\AdminTemplatesController::class . '@update');
});
