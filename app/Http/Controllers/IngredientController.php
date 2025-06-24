<?php

namespace App\Http\Controllers;

use App\Services\IngredientService;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function show(Request $request, IngredientService $ingredientService)
    {
        $ingredientName = $request->query('ingredient'); // ?name=Apple

        if (!$ingredientName) {
            return response()->json(['error' => 'Missing ingredient name'], 422);
        }

        $ingredient = $ingredientService->fetchByName($ingredientName);

        if (!$ingredient) {
            return response()->json(['error' => 'Ingredient not found or API failed'], 404);
        }

        return response()->json([
            'name' => $ingredient->name,
            'carbs' => $ingredient->carbs,
            'fat' => $ingredient->fat,
            'protein' => $ingredient->protein,
        ]);
    }

    public function store(String $name, Request $request, IngredientService $ingredientService)
    {
        $validated = $request->validate([
            'carbs' => 'required|numeric',
            'fat' => 'required|numeric',
            'protein' => 'required|numeric',
        ]);

        $data = array_merge($validated, ['name' => $name]);

        $success = $ingredientService->createIngredient($data);

        return $success
            ? response()->json(['message' => 'Ingredient created successfully.'])
            : response()->json(['error' => 'Failed to create ingredient.'], 500);
    }
}
