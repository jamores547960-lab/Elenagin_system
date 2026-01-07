<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class InventoryAdjustment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'adjustment_id',
        'item_id',
        'user_id',
        'adjustment_type',
        'quantity',
        'reason',
        'cost_impact',
        'adjustment_date',
        'approved_by',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'cost_impact' => 'decimal:2',
        'adjustment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'spoilage' => ['label' => 'Spoilage', 'color' => 'danger'],
            'wastage' => ['label' => 'Wastage', 'color' => 'warning'],
            'damage' => ['label' => 'Damage', 'color' => 'danger'],
            'expired' => ['label' => 'Expired', 'color' => 'dark'],
            'theft' => ['label' => 'Theft', 'color' => 'danger'],
            'correction' => ['label' => 'Correction', 'color' => 'info'],
            'return' => ['label' => 'Return', 'color' => 'success'],
        ];

        return $labels[$this->adjustment_type] ?? ['label' => 'Unknown', 'color' => 'secondary'];
    }
}
