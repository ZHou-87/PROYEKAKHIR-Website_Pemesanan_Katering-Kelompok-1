<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'price' => (float) $this->price,
            'min_order' => (int) $this->min_order,
            'image' => $this->image,
            'is_active' => (bool) $this->is_active,
            'order_items_count' => $this->whenCounted('orderItems'),
            'order_items' => $this->whenLoaded('orderItems'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
