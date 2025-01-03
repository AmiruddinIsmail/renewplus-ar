<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'addresses' => $this->whenLoaded('addresses'),
            'order' => OrderResource::make($this->whenLoaded('order')),
            'unresolved_invoices_amount' => $this->convertToHumanReadable($this->invoices_sum_unresolved_amount ?? 0),
        ];
    }
}
