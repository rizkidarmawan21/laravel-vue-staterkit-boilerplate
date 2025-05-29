<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'base_product_id',
        'warehouse_id',
        'production_code',
        'code',
        'description',
        'item_quantity',
        'status',
        'selling_price',
        'unit',
        'item_unit',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'item_quantity' => 'integer',
        'status' => 'integer',
        'selling_price' => 'decimal:2',
    ];

    /**
     * Product status constants
     */
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_OUT_OF_STOCK = 3;
    const STATUS_DISCONTINUED = 4;
    const STATUS_ARCHIVED = 5;
    const STATUS_DELETED = 6;

    /**
     * Get the base product that owns the product.
     */
    public function baseProduct(): BelongsTo
    {
        return $this->belongsTo(BaseProduct::class, 'base_product_id');
    }

    /**
     * Get the warehouse where the product was produced.
     */
    public function productionWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    /**
     * Get the owner model (polymorphic).
     */
    public function ownership(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the partner product associated with the product.
     */
    public function partnerProduct(): HasOne
    {
        return $this->hasOne(PartnerProduct::class, 'product_id');
    }

    /**
     * Get the stock histories for the product.
     */
    public function stockHistories(): HasMany
    {
        return $this->hasMany(LogProductStockHistory::class, 'product_id');
    }
}
