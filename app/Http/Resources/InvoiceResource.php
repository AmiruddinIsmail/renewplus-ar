<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalPaid = $this->amount - $this->unresolved_amount;

        return [
            'id' => $this->id,
            'reference_no' => $this->reference_no,
            'issue_at' => $this->issue_at,
            'due_at' => $this->due_at,
            'status' => $this->status,
            'subscription_amount' => $this->convertToHumanReadable($this->subscription_amount),
            'charge_amount' => $this->convertToHumanReadable($this->charge_amount),
            'amount' => $this->convertToHumanReadable($this->amount),
            'credit_paid' => $this->convertToHumanReadable($this->credit_paid),
            'over_paid' => $this->convertToHumanReadable($this->over_paid),
            'unresolved' => $this->unresolved,
            'unresolved_amount' => $this->convertToHumanReadable($this->unresolved_amount),
            'paid_amount' => $this->convertToHumanReadable($totalPaid),
            'created_at' => $this->created_at,
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'order' => OrderResource::make($this->whenLoaded('order')),
            'charges' => ChargeResource::collection($this->whenLoaded('charges')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'pivot' => $this->whenPivotLoaded('invoice_payment', function () {
                return [
                    'amount' => $this->convertToHumanReadable($this->pivot->amount),
                ];
            }),
        ];
    }
}
