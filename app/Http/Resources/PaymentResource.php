<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'paid_at' => $this->paid_at,
            'amount' => $this->convertToHumanReadable($this->amount),
            'unresolved' => $this->unresolved,
            'unresolved_amount' => $this->convertToHumanReadable($this->unresolved_amount),
            'created_at' => $this->created_at,
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'order' => OrderResource::make($this->whenLoaded('order')),
            'charges' => ChargeResource::collection($this->whenLoaded('charges')),
            'invoices' => InvoiceResource::collection($this->whenLoaded('invoices')),
            'pivot' => $this->whenPivotLoaded('invoice_payment', function () {
                return [
                    'amount' => $this->convertToHumanReadable($this->pivot->amount),
                ];
            }),
        ];
    }
}
