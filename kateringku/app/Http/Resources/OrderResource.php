<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_code' => $this->order_code,
            'event_date' => $this->event_date?->format('Y-m-d'),
            'delivery_method' => $this->delivery_method,
            'distance_km' => (float) $this->distance_km,
            'delivery_fee' => (float) $this->delivery_fee,
            'delivery_address' => $this->delivery_address,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'total_price' => (float) $this->total_price,
            'payment_method' => $this->payment_method,
            'payment_expired_at' => $this->payment_expired_at?->toISOString(),
            'order_status' => $this->order_status,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]),
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($item) => [
                'id' => $item->id,
                'menu_id' => $item->menu_id,
                'quantity' => (int) $item->quantity,
                'price_per_portion' => (float) $item->price_per_portion,
                'menu' => $item->relationLoaded('menu') && $item->menu ? [
                    'id' => $item->menu->id,
                    'name' => $item->menu->name,
                    'category' => $item->menu->category,
                ] : null,
            ])),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
