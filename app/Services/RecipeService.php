<?php

namespace App\Services;

use App\Models\Recipe;
use App\Models\RecipeIngredient;

class RecipeService
{
    protected $ingredientService;

    public function __construct(IngredientService $ingredientService)
    {
        $this->ingredientService = $ingredientService;
    }

    public function createOrUpdateRecipeWithIngredients(string $name, string $description, array $ingredients): array
    {
        $missingIngredients = [];

        foreach ($ingredients as $ingredientName) {
            $ingredient = $this->ingredientService->fetchByName($ingredientName);
            if (is_null($ingredient)) {
                $missingIngredients[] = $ingredientName;
            }
        }

        if (!empty($missingIngredients)) {
            return [
                'success' => false,
                'invalid ingredients' => $missingIngredients,
                'recipe' => null,
                'created' => false,

            ];
        }

        // Check if recipe exists
        $recipe = Recipe::where('name', $name)->first();

        $wasNew = !$recipe;

        if ($recipe) {
            // Update description
            $recipe->update(['description' => $description]);

            // Remove old ingredients
            $recipe->ingredients()->delete();
        } else {
            // Create new recipe
            $recipe = Recipe::create([
                'name' => $name,
                'description' => $description,
            ]);
        }

        $now = now();
        $recipeIngredientsData = array_map(function ($ingredientName) use ($recipe, $now) {
            return [
                'recipe_id' => $recipe->id,
                'ingredient_name' => $ingredientName,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $ingredients);

        RecipeIngredient::insert($recipeIngredientsData);

        return [
            'success' => true,
            'invalid ingredients' => null,
            'recipe' => $recipe,
            'created' => $wasNew,
        ];
    }
}
