<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'month',
        'year',
        'room_rental',
        'electricity',
        'water',
        'wifi',
        'late_fee',
        'total_amount',
        'paid_amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'month' => 'integer',
        'year' => 'integer',
        'room_rental' => 'integer',
        'electricity' => 'integer',
        'water' => 'integer',
        'wifi' => 'integer',
        'late_fee' => 'integer',
        'total_amount' => 'integer',
        'paid_amount' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    public function remainingAmount(): int
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }
}
