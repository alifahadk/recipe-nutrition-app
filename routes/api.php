<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

// Ingredients
Route::get('/ingredients', [IngredientController::class, 'show']);
Route::post('/ingredient/{name}', [IngredientController::class, 'store']);

// Recipes
Route::post('/recipe/{name}', [RecipeController::class, 'store']);
Route::get('/recipe/{name}', [RecipeController::class, 'show']);
Route::delete('/recipe/{name}', [RecipeController::class, 'destroy']);
