<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Sales extends Model
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
        'phone',
        'address',
        'email',
        'account_id',
    ];

    /**
     * Get the user account associated with the sales.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id');
    }

    /**
     * Get the partners associated with the sales.
     */
    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class, 'sales_id');
    }

    /**
     * Get all of the products owned by the sales.
     */
    public function products(): MorphMany
    {
        return $this->morphMany(Product::class, 'ownership');
    }

    /**
     * Get the stock histories where this sales is the last location.
     */
    public function lastLocationStockHistories(): MorphMany
    {
        return $this->morphMany(LogProductStockHistory::class, 'last_location');
    }

    /**
     * Get the stock histories where this sales is the new location.
     */
    public function newLocationStockHistories(): MorphMany
    {
        return $this->morphMany(LogProductStockHistory::class, 'new_location');
    }
}
