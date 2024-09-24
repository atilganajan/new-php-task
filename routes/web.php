<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use App\Routing\Route;


Route::middleware([AuthMiddleware::class], function() {
    Route::get('/user/{id:\d+}?', [UserController::class, 'show']); // Opsiyonel parametre
    Route::get('/user/{id}/translations/{language_id}', [UserController::class, 'showMultiple']); // Çoklu parametre
});