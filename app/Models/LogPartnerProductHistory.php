<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPartnerProductHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'partner_product_id',
        'unit_quantity',
        'last_quantity',
        'new_quantity',
        'created_by',
        'note',
        'system_note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_quantity' => 'integer',
        'last_quantity' => 'integer',
        'new_quantity' => 'integer',
    ];

    /**
     * Get the partner product that owns the history.
     */
    public function partnerProduct(): BelongsTo
    {
        return $this->belongsTo(PartnerProduct::class, 'partner_product_id');
    }

    /**
     * Get the user who created the history.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
