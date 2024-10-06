<?php

namespace App\Http\Resources;

use App\Utils\Helper;
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
            'active' => $this->active,
            'tenure' => $this->tenure,
            'subscription_fee' => Helper::formatMoney($this->subscription_fee),
            'contract_at' => $this->contract_at->format('Y-m-d'),
            'completed_at' => $this->completed_at,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'addresses' => $this->whenLoaded('addresses'),
            'unresolved_invoices_amount' => Helper::formatMoney($this->invoices_sum_unresolved_amount ?? 0),
        ];
    }
}
