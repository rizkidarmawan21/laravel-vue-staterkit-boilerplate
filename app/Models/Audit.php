<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Audit extends Model
{
    protected $fillable = [
        'user_type',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getOldValuesAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function getNewValuesAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : 'System';
    }

    public function getEventLabelAttribute()
    {
        return match ($this->event) {
            'created' => 'Dibuat',
            'updated' => 'Diperbarui',
            'deleted' => 'Dihapus',
            'restored' => 'Dipulihkan',
            'login' => 'Login',
            'logout' => 'Logout',
            'login_failed' => 'Login Gagal',
            default => ucfirst($this->event)
        };
    }

    public function getModelNameAttribute()
    {
        // Special handling for authentication events
        if (in_array($this->event, ['login', 'logout', 'login_failed'])) {
            return 'Autentikasi';
        }

        $modelClass = $this->auditable_type;
        if (!$modelClass) return 'Unknown';

        $className = class_basename($modelClass);
        return match ($className) {
            'User' => 'Pengguna',
            'Partner' => 'Mitra',
            'Sales' => 'Sales',
            'Warehouse' => 'Gudang',
            default => $className
        };
    }
}
