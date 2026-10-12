<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'type',
        'price',
        'size',
        'description',
        'facilities',
        'is_available',
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_available' => 'boolean',
        'price' => 'integer',
        'size' => 'integer',
    ];

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function activeTenant()
    {
        return $this->hasOne(Tenant::class)->where('status', 'active');
    }
}
