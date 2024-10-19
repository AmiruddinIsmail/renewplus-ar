<?php

namespace App\Http\Resources;

use App\Utils\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditResource extends JsonResource
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
            'reference_no' => $this->reference_no,
            'customer_id' => $this->customer_id,
            'amount' => Helper::formatMoney($this->amount),
            'unresolved' => $this->unresolved,
            'unresolved_amount' => Helper::formatMoney($this->unresolved_amount),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at,
            'customer' => $this->whenLoaded('customer'),
        ];
    }
}
