<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChargeResource extends JsonResource
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
            'order_id' => $this->order_id,
            'reference_no' => $this->reference_no,
            'charged_at' => $this->charged_at,
            'type' => $this->type,
            'amount' => $this->convertToHumanReadable($this->amount),
            'unresolved' => $this->unresolved,
            'invoice_id' => $this->invoice_id,
            'created_at' => $this->created_at,
        ];
    }
}
