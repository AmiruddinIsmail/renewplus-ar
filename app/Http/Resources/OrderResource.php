<?php

namespace App\Http\Resources;

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
            'customer_id' => $this->customer_id,
            'reference_no' => $this->reference_no,
            'tenure' => $this->tenure,
            'subscription_amount' => $this->convertToHumanReadable($this->subscription_amount),
            'contract_at' => $this->contract_at->format('Y-m-d'),
            'completed_at' => $this->completed_at,
            'payment_gateway' => $this->payment_gateway,
            'payment_reference' => $this->payment_reference,
            'active' => $this->active,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
