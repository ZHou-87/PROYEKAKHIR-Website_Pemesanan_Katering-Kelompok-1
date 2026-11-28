<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    // user_id sengaja tidak dimasukkan ke $fillable untuk mencegah mass-assignment IDOR.
    protected $fillable = [
        'order_code',
        'event_date',
        'delivery_method',
        'distance_km',
        'delivery_fee',
        'delivery_address',
        'phone',
        'notes',
        'total_price',
        'payment_method',
        'payment_expired_at',
        'order_status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'distance_km' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total_price' => 'decimal:2',
            'payment_expired_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
