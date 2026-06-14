<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'middle_name', 'nickname'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
    public function lastBook()
    {
        return $this->hasOne(Book::class)->latestOfMany();
    }
}
