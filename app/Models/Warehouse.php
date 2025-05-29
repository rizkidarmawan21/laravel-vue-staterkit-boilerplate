<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Warehouse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'address',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the products produced in this warehouse.
     */
    public function producedProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'warehouse_id');
    }

    /**
     * Get all of the products owned by the warehouse.
     */
    public function ownedProducts(): MorphMany
    {
        return $this->morphMany(Product::class, 'ownership');
    }

    /**
     * Get the stock histories where this warehouse is the last location.
     */
    public function lastLocationStockHistories(): MorphMany
    {
        return $this->morphMany(LogProductStockHistory::class, 'last_location');
    }

    /**
     * Get the stock histories where this warehouse is the new location.
     */
    public function newLocationStockHistories(): MorphMany
    {
        return $this->morphMany(LogProductStockHistory::class, 'new_location');
    }
}
