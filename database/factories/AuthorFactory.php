<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'middle_name' => fake()->optional(0.8)->firstName(),
            'nickname' => fake()->optional(0.3)->userName(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Author $author) {
            $placeholder = database_path('seeders/media/placeholder.png');
            if (file_exists($placeholder)) {
                $author->addMedia($placeholder)
                    ->preservingOriginal()
                    ->toMediaCollection('avatar');
            }
        });
    }

}
