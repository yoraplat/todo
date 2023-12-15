<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use App\Models\User;
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

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/users/auth', AuthController::class);
  // Route::get('/users/{user}', [UserController::class, 'show']);
  
  Route::get('/todos/user/{user}', [TodoController::class, 'getByUserId']);
  Route::patch('/todos/{todo}/update', [TodoController::class, 'update']);
  Route::post('/todos', [TodoController::class, 'store']);
  Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);
});
