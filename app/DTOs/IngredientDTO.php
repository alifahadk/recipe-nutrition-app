<?php

namespace App\DTOs;

class IngredientDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $carbs,
        public readonly float $fat,
        public readonly float $protein,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            carbs: (float) ($data['carbs'] ?? 0),
            fat: (float) ($data['fat'] ?? 0),
            protein: (float) ($data['protein'] ?? 0),
        );
    }
}
