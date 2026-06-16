<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Filters\BookFilter;
use App\Filters\BookSorter;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Book extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    protected $fillable = [
        'author_id',
        'genre_id',
        'title',
        'description',
        'price',
        'old_price',
        'year'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function scopeFilter(Builder $query, BookFilter $filter): Builder
    {
        return $filter->apply($query);
    }
    public function scopeSort(Builder $query, BookSorter $sorter): Builder
    {
        return $sorter->apply($query);
    }
}

