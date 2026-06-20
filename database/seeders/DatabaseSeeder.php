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
        $mediaPath = database_path('seeders/media');
        if (!file_exists($mediaPath)) {
            mkdir($mediaPath, 0755, true);
        }

        $placeholder = $mediaPath . '/placeholder.png';
        if (!file_exists($placeholder)) {
            $img = imagecreatetruecolor(200, 200);
            $bg = imagecolorallocate($img, 150, 150, 150);
            imagefill($img, 0, 0, $bg);
            imagejpeg($img, $placeholder);
            imagedestroy($img);
        }

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

        \App\Models\Admin::updateOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin',
            'password' => 'admin',
        ]);
    }
}
