<?php

use App\Http\Controllers\IngredientController;
use Illuminate\Support\Facades\Route;

// Ingredients
Route::get('/ingredients', [IngredientController::class, 'show']);
Route::post('/ingredient/{name}', [IngredientController::class, 'store']);
