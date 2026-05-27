<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

use Filament\Models\Contracts\FilamentUser;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function canAccessFilament(): bool
    {
        return in_array($this->role, ['owner', 'kasir', 'dapur']);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Periksa apakah user adalah owner
     */
    // public function isOwner(): bool
    // {
    //     return $this->hasRole('owner');
    // }

    // /**
    //  * Periksa apakah user adalah kasir
    //  */
    // public function isKasir(): bool
    // {
    //     return $this->hasRole('kasir');
    // }

    // /**
    //  * Periksa apakah user adalah staff dapur
    //  */
    // public function isDapur(): bool
    // {
    //     return $this->hasRole('dapur');
    // }

    /**
     * Dapatkan nama role yang dapat dibaca
     */
    public function getRoleNameAttribute(): string
    {
        return match ($this->role) {
            'owner' => 'Owner',
            'kasir' => 'Kasir',
            'dapur' => 'Dapur',
            default => 'Unknown'
        };
    }

    /**
     * Scope untuk filter user berdasarkan role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk owner
     */
    public function scopeOwners($query)
    {
        return $query->byRole('owner');
    }

    /**
     * Scope untuk kasir
     */
    public function scopeKasirs($query)
    {
        return $query->byRole('kasir');
    }

    /**
     * Scope untuk staff dapur
     */
    public function scopeDapurs($query)
    {
        return $query->byRole('dapur');
    }

    /**
     * Dapatkan dashboard URL berdasarkan role
     */
    public function getDashboardUrlAttribute(): string
    {
        return match ($this->role) {
            'owner' => '/owner',
            'kasir' => '/kasir',
            'dapur' => '/dapur',
            default => '/'
        };
    }

    /**
     * Konstanta untuk role yang tersedia
     */
    public const ROLES = [
        'owner' => 'Owner',
        'kasir' => 'Kasir',
        'dapur' => 'Dapur',
    ];

    /**
     * Dapatkan semua role yang tersedia
     */
    public static function getAvailableRoles(): array
    {
        return self::ROLES;
    }
}
