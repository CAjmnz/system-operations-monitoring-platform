<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'usertype',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS (matches your system style)
    |--------------------------------------------------------------------------
    */

    public function role(): string
    {
        return match ($this->usertype) {
            1 => 'admin',
            2 => 'treasury',
            3 => 'operator',
            4 => 'user',
            default => 'user',
        };
    }

    public function isAdmin(): bool
    {
        return $this->usertype === 1;
    }

    public function isTreasury(): bool
    {
        return $this->usertype === 2;
    }

    public function isOperator(): bool
    {
        return $this->usertype === 3;
    }
}