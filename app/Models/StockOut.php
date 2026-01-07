<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOut extends Model
{
    use SoftDeletes;

    protected $table = 'stock_out';

    // Use standard auto-increment id as primary key
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'stockout_id',
        'item_id',
        'user_id',
        'quantity',
        'stockout_date',
        'reference_type',
        'reference_id',
        'reason',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stockout_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Polymorphic owner (Sale, Service, etc.)
     * This allows $stockOut->reference to return the Sale model instance.
     */
    public function reference()
    {
        return $this->morphTo();
    }
}