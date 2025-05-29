<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'address',
        'email',
        'password',
        'is_active',
        'role',
        'image_profile'
    ];

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
            'is_active' => 'boolean',
            'role' => 'integer',
        ];
    }

    /**
     * Role constants
     */
    const ADMINISTRATOR = 0;
    const ADMIN = 1;
    const WAREHOUSE = 2;
    const PRODUCTION = 4;
    const SALES = 5;
    const MITRA = 6;

    /**
     * Get the sales profile associated with the user.
     */
    public function salesProfile(): HasOne
    {
        return $this->hasOne(Sales::class, 'account_id');
    }

    /**
     * Get the partner profile associated with the user.
     */
    public function partnerProfile(): HasOne
    {
        return $this->hasOne(Partner::class, 'account_id');
    }
}
