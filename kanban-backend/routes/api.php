<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\TaskController;

// Board routes
Route::apiResource('boards', BoardController::class);

// Card routes
Route::apiResource('boards.cards', CardController::class);

// Task routes
Route::apiResource('boards.cards.tasks', TaskController::class);