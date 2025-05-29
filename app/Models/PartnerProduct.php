<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartnerProduct extends Model
{
    public const UNIT_BOTOL = 'BOTOL';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'partner_id',
        'unit_quantity',
        'unit',
    ];

    /**
     * Get the product that owns the partner product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the partner that owns the partner product.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    /**
     * Get the stock histories where this partner product is the new location.
     */
    public function newLocationStockHistories(): MorphMany
    {
        return $this->morphMany(LogProductStockHistory::class, 'new_location');
    }

    /**
     * Get the histories for this partner product.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(LogPartnerProductHistory::class, 'partner_product_id');
    }
}
