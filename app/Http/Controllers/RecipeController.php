<?php

namespace App\Http\Controllers;

use App\Services\RecipeService;
use Illuminate\Http\Request;

class RecipeController extends Controller{

    public function store(string $name, Request $request, RecipeService $recipeService)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*' => 'string',
        ]);

        $result = $recipeService->createOrUpdateRecipeWithIngredients(
            $name,
            $validated['description'],
            $validated['ingredients']
        );

        if (!$result['success']) {
            return response()->json([
                'error' => 'Some ingredients were not found.',
                'invalid ingredients' => $result['invalid ingredients'],
            ], 400);
        }

        return response()->json([
            'message' => 'Recipe upserted successfully.',
            'recipe' => $result['recipe'],
            'created' => $result['created'],

        ], 200);
    }
}
