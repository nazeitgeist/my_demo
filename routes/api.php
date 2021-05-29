<?php

use App\Http\Controllers\API\DeveloperController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login']);
Route::post('forgotPassword', [UserController::class, 'forgotPassword']);
Route::post('submitResetPasswordForm', [UserController::class, 'submitResetPasswordForm']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'developers', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [DeveloperController::class, 'index']);
    Route::post('add', [DeveloperController::class, 'add']);
    Route::get('edit/{id}', [DeveloperController::class, 'edit']);
    Route::post('update/{id}', [DeveloperController::class, 'update']);
    Route::delete('delete/{id}', [DeveloperController::class, 'delete']);
	Route::post('deleteMultitple', [DeveloperController::class, 'deleteMultitple']);
});