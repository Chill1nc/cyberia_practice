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

        $publishers = ['Азбука', 'Эксмо', 'АСТ', 'МИФ', 'Питер', 'Альпина', 'Росмэн'];
        $coverTypes = ['твердая', 'мягкая', 'суперобложка'];
        $ageLimits = ['0+', '6+', '12+', '16+', '18+'];

        $width = fake()->numberBetween(100, 200);
        $height = fake()->numberBetween(150, 280);
        $depth = fake()->numberBetween(5, 30);

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'price' => $price,
            'old_price' => $oldPrice,
            'year' => fake()->numberBetween(1900, 2026),
            'publisher' => fake()->randomElement($publishers),
            'pages' => fake()->numberBetween(80, 1200),
            'size' => "{$width}x{$height}x{$depth} мм",
            'cover_type' => fake()->randomElement($coverTypes),
            'weight' => fake()->numberBetween(100, 900),
            'age_limit' => fake()->randomElement($ageLimits),
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

            $reviewCount = fake()->numberBetween(0, 8);
            for ($i = 0; $i < $reviewCount; $i++) {
                $book->reviews()->create([
                    'author_name' => fake()->name(),
                    'comment' => fake()->paragraph(),
                    'rating' => fake()->numberBetween(1, 5),
                ]);
            }
        });
    }
}
