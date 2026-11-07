<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GarmentTag extends Model
{
    use BelongsToTenant;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'garment_tags';

    protected $fillable = ['tenant_id','order_id','order_item_id','tag_code','barcode','qr_payload','rfid','status','rack_location','bag_code','color','brand','fabric','inspection','stains','damages','care_label_photo','photo_before','photo_after','customer_signature'];

    protected function casts(): array
    {
        return [
            'inspection' => 'array',
            'stains' => 'array',
            'damages' => 'array',
        ];
    }



    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
