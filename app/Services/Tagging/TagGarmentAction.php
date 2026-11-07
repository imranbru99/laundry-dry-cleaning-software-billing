<?php

declare(strict_types=1);

namespace App\Services\Tagging;

use App\Models\GarmentTag;
use App\Models\Order;
use Illuminate\Support\Str;

final class TagGarmentAction
{
    public function handle(Order $order, array $data): GarmentTag
    {
        $code = $data['tag_code'] ?? ('TAG-'.strtoupper(Str::random(10)));

        return GarmentTag::query()->create([
            'tenant_id' => $order->tenant_id,
            'order_id' => $order->id,
            'order_item_id' => $data['order_item_id'] ?? null,
            'tag_code' => $code,
            'barcode' => $data['barcode'] ?? $code,
            'qr_payload' => $data['qr_payload'] ?? url('/track/'.$code),
            'rfid' => $data['rfid'] ?? null,
            'status' => 'intake',
            'color' => $data['color'] ?? null,
            'brand' => $data['brand'] ?? null,
            'fabric' => $data['fabric'] ?? null,
            'inspection' => $data['inspection'] ?? [],
            'stains' => $data['stains'] ?? [],
            'damages' => $data['damages'] ?? [],
            'care_label_photo' => $data['care_label_photo'] ?? null,
            'photo_before' => $data['photo_before'] ?? null,
            'customer_signature' => $data['customer_signature'] ?? null,
        ]);
    }
}