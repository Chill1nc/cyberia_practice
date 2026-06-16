<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'login_code',
        'login_code_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'login_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'login_code_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'favorites')
            ->withTimestamps();
    }
}
