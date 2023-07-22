<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {

    // task route
    Route::get('tasks', [TaskController::class, 'getTasks']);
    Route::post('create/task', [TaskController::class, 'store']);
    Route::post('/update/task/{id}', [TaskController::class, 'update']);
    Route::get('get/task/{id}', [TaskController::class, 'getTask']);
    Route::get('delete/task/{id}', [TaskController::class, 'delete']);

    // user route 
    Route::get('get/users', [AuthenticatedSessionController::class, 'getUsers']);
    Route::post('logout', [AuthenticatedSessionController::class, 'logout']);
});



Route::post('login', [AuthenticatedSessionController::class, 'login']);
Route::post('register', [AuthenticatedSessionController::class, 'register']);

