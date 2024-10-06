<?php

namespace App\Http\Resources;

use App\Utils\Helper;
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
        return [
            'id' => $this->id,
            'reference_no' => $this->reference_no,
            'issue_at' => $this->issue_at,
            'due_at' => $this->due_at,
            'status' => $this->status,
            'subscription_fee' => Helper::formatMoney($this->subscription_fee),
            'charge_fee' => Helper::formatMoney($this->charge_fee),
            'unresolved' => $this->unresolved,
            'unresolved_amount' => Helper::formatMoney($this->unresolved_amount),
            'paid_amount' => Helper::formatMoney($this->payments_sum_invoice_paymentamount ?? 0),
            'created_at' => $this->created_at,
            'customer' => $this->whenLoaded('customer'),

        ];
    }
}
