<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_method',
        'amount_received',
        'change_amount',
        'sale_date',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'sale_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user (cashier) who processed the sale
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in this sale
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the stock out records for this sale
     */
    public function stockOuts()
    {
        return $this->morphMany(StockOut::class, 'reference');
    }
    
    /**
     * Accessor to ensure sale_date is always in Asia/Manila timezone
     */
    public function getSaleDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Asia/Manila') : null;
    }
}