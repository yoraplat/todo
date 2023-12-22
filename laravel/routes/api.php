<?php

use App\Http\Controllers\Admin\TodoController as AdminTodoController;
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

  Route::prefix('todos')->group(function () {
    Route::get('/', [TodoController::class, 'index']);
    Route::patch('/{todo}', [TodoController::class, 'update']);
    Route::post('/', [TodoController::class, 'store']);
    Route::delete('/{todo}', [TodoController::class, 'destroy']);
    
  });
  
  Route::middleware(['can:edit user tasks', 'can:delete user tasks'])->prefix('admin/todos')->group(function () {
    Route::get('/', [AdminTodoController::class, 'index']);
    Route::patch('/{todo}', [AdminTodoController::class, 'update']);
    Route::post('/', [AdminTodoController::class, 'store']);
    Route::delete('/{todo}', [AdminTodoController::class, 'destroy']);
  });
});
