<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Partner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'store_name',
        'store_phone',
        'store_address',
        'store_latitude',
        'store_longitude',
        'owner_name',
        'owner_phone',
        'owner_address',
        'account_id',
        'sales_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'store_latitude' => 'decimal:8',
        'store_longitude' => 'decimal:8',
    ];

    /**
     * Get the user account associated with the partner.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id');
    }

    /**
     * Get the sales associated with the partner.
     */
    public function sales(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sales_id');
    }

    /**
     * Get the partner product associated with the partner.
     */
    public function partnerProduct(): HasOne
    {
        return $this->hasOne(PartnerProduct::class, 'partner_id');
    }
}
