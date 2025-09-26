<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fio' => $this->fio,
            'phone' => $this->phone,
            'email' => $this->email,
            'inn' => $this->inn,
            'company' => $this->company,
            'address' => $this->address,
            'status' => $this->status,
            'status_label' => Order::STATUS_LABELS[$this->status],
            'products' => $this->whenLoaded('products'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
