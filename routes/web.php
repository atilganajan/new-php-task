<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use App\Routing\Route;


Route::middleware([AuthMiddleware::class], function() {
    Route::get('/user_reg/{id:\d+}', [UserController::class, 'show']); // regex parametre
    Route::get('/user_op/{id?}', [UserController::class, 'showOptional']); // opsiyonel parametre
    Route::get('/user/{id}/translations/{language_id}', [UserController::class, 'showMultiple']); // Çoklu parametre
});