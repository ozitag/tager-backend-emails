<?php

use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Mail\Controllers\AdminMailLogsController;
use OZiTAG\Tager\Backend\Mail\Controllers\AdminMailTemplatesController;
use OZiTAG\Tager\Backend\Mail\Controllers\AdminMailController;

Route::group(['prefix' => 'admin/mail', 'middleware' => ['passport:administrators', 'auth:api']], function () {
    Route::get('/info', [AdminMailController::class, 'info']);
    Route::get('/service-templates', [AdminMailController::class, 'serviceTemplates']);

    Route::get('/logs', [AdminMailLogsController::class, 'index']);

    Route::get('/templates', [AdminMailTemplatesController::class, 'index']);
    Route::get('/templates/{id}', [AdminMailTemplatesController::class, 'view']);
    Route::put('/templates/{id}', [AdminMailTemplatesController::class, 'update']);
});
