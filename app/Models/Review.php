<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $withCount = ['likes', 'dislikes'];
    protected $appends = ['user_rating'];

    protected $fillable = [
        'book_id',
        'author_name',
        'comment',
        'rating',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function likes()
    {
        return $this->hasMany(ReviewRate::class)->where('rating', 1);
    }

    public function dislikes()
    {
        return $this->hasMany(ReviewRate::class)->where('rating', -1);
    }

    public function currentUserRate()
    {
        return $this->hasOne(ReviewRate::class)->where('user_id', auth('sanctum')->id());
    }

    public function getUserRatingAttribute()
    {
        if ($this->relationLoaded('currentUserRate')) {
            return $this->currentUserRate ? (int)$this->currentUserRate->rating : null;
        }

        $user = auth('sanctum')->user();
        if (!$user) {
            return null;
        }
        $rate = \App\Models\ReviewRate::where('user_id', $user->id)
            ->where('review_id', $this->id)
            ->first();
        return $rate ? (int)$rate->rating : null;
    }
}
