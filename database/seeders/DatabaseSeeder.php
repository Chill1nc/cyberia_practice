<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            GenreSeeder::class,
            AuthorSeeder::class,
            BookSeeder::class,
        ]);

        User::factory()->create([
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'test@example.com',
            'phone' => '+79991112233',
        ]);
    }

}
