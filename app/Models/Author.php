<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Author extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['first_name', 'last_name', 'middle_name', 'nickname'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }
    public function books()
    {
        return $this->hasMany(Book::class);
    }
    public function lastBook()
    {
        return $this->hasOne(Book::class)->latestOfMany();
    }
}
