<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'tenant_id', 'branch_id', 'customer_id', 'number', 'type', 'status', 'priority',
        'estimated_ready_at', 'ready_at', 'delivered_at', 'special_instructions',
        'subtotal', 'tax_total', 'discount_total', 'delivery_fee', 'grand_total',
        'amount_paid', 'currency', 'created_by', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'estimated_ready_at' => 'datetime',
            'ready_at' => 'datetime',
            'delivered_at' => 'datetime',
            'meta' => 'array',
            'subtotal' => 'integer',
            'tax_total' => 'integer',
            'discount_total' => 'integer',
            'delivery_fee' => 'integer',
            'grand_total' => 'integer',
            'amount_paid' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function garmentTags(): HasMany
    {
        return $this->hasMany(GarmentTag::class);
    }
}
