<?php

namespace App\Services;

use App\DTOs\IngredientDTO;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class IngredientService
{
    public function fetchByName(string $name): ?IngredientDTO
    {
        //TODO: Move retry params to config
        try {
            $response = Http::retry(3, 200)
                ->timeout(5)
                ->withBasicAuth(
                    Config::get('services.ingredients.username'),
                    Config::get('services.ingredients.password')
                )
                ->get(Config::get('services.ingredients.base_url'), [
                    'ingredient' => $name,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (!is_array($data)) {
                    Log::warning("Unexpected response structure", [
                        'response' => $response->body(),
                    ]);
                    return null;
                }

                return IngredientDTO::fromArray($data);
            }

            Log::warning("Ingredient fetch failed", [
                'ingredient' => $name,
                'status' => $response->status(),
            ]);
        } catch (Throwable $e) {
            Log::error("Exception while fetching ingredient", [
                'ingredient' => $name,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    public function createIngredient(array $data): bool
    {
        try {
            $response = Http::asForm()
                ->withBasicAuth(
                    Config::get('services.ingredients.username'),
                    Config::get('services.ingredients.password')
                )
                ->retry(3, 200)
                ->timeout(5)
                ->post(Config::get('services.ingredients.base_url'), [
                    'name'    => $data['name'],
                    'carbs'   => $data['carbs'],
                    'fat'     => $data['fat'],
                    'protein' => $data['protein'],
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('Failed to create ingredient', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } catch (Throwable $e) {
            Log::error('Exception while creating ingredient', [
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }
}
