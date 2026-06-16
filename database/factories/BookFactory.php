<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 200, 2000);
        $oldPrice = fake()->optional(0.4)->randomFloat(2, $price + 100, $price + 500);

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'price' => $price,
            'old_price' => $oldPrice,
            'year' => fake()->numberBetween(1900, 2026),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Book $book) {
            $placeholder = database_path('seeders/media/placeholder.png');
            if (file_exists($placeholder)) {
                for ($i = 0; $i < 3; $i++) {
                    $book->addMedia($placeholder)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                }
            }
        });
    }

}
