<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LogProductStockHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'last_location_type',
        'last_location_id',
        'new_location_type',
        'new_location_id',
        'created_by',
        'unit_quantity',
        'last_unit_quantity',
        'last_status',
        'new_unit_quantity',
        'new_status',
        'system_note',
        'note',
        'is_current',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_quantity' => 'integer',
        'last_unit_quantity' => 'integer',
        'new_unit_quantity' => 'integer',
        'is_current' => 'boolean',
    ];

    /**
     * Get the product that owns the stock history.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the user who created the stock history.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the last location model (polymorphic).
     */
    public function lastLocation(): MorphTo
    {
        return $this->morphTo('last_location');
    }

    /**
     * Get the new location model (polymorphic).
     */
    public function newLocation(): MorphTo
    {
        return $this->morphTo('new_location');
    }
}
