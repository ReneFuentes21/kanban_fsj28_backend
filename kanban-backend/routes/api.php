<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});
/*
// Board routes
Route::apiResource('boards', BoardController::class);

// Card routes
Route::apiResource('boards.cards', CardController::class);

// Task routes
Route::apiResource('boards.cards.tasks', TaskController::class);
*/

Route::prefix('v1')->group(function() {
    //boards
    Route::get('/boards', [BoardController::class, 'index']);//todos los tableros
    Route::get('/boards/{id}', [BoardController::class, 'show']);//tablero por id
    Route::post('/boards',[BoardController::class,'store']);//crear tablero
    Route::put('/boards/{id}',[BoardController::class, 'update']);//actulizar tablero
    Route::patch('/boards/{id}',[BoardController::class, 'update']);//actulizar tablero
    Route::delete('/boards/{id}',[BoardController::class,'destroy']);//eliminar tablero

    //cards
    //Route::get('/cards',[CardController::class, 'index']);//todas las tarjetas
    Route::get('/boards/{boardId}/cards', [CardController::class, 'index']);
    Route::get('/cards/{id}',[CardController::class, 'show']);//tarjeta por id

    Route::post('boards/{boardId}/cards', [CardController::class, 'store']);
    //Route::post('cards',[CardController::class, 'store']);//crear tarjeta

    Route::put('/cards/{card}',[CardController::class, 'update']);//actulizar tarjeta
    Route::patch('/cards/{id}/move',[CardController::class, 'updateId']);//actualizar id de la tarjeta
    Route::delete('cards/{id}',[CardController::class, 'destroy']);//borrar tarjeta
    
    //tasks
    Route::get('/tasks', [TaskController::class, 'index']);//tpdas las tareas
    Route::get('/tasks/{id}', [TaskController::class, 'show']);//tarea por id
    Route::post('/tasks',[TaskController::class, 'store']);//crear tarea
    Route::put('/tasks/{id}', [TaskController::class,'update']);//editar tarea
    Route::patch('/tasks/update-card-id/{id}', [TaskController::class,'updateId']);//actualizar id
    Route::get('/tasks/daysLeft/{id}', [TaskController::class, 'daysLeft']);//ver cuantos dias quedan
    Route::delete('/tasks/{id}',[TaskController::class, 'destroy']);//borrar tarea

});