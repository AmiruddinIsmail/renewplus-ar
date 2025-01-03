<?php

namespace App\Features\Automation\Pipes;

use App\Mail\NotifyInvoiceCreated;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNotification implements ShouldQueueAfterCommit
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(Invoice $invoice, $next): mixed
    {
        $invoice->load('customer', 'order');

        if ($invoice->order->notify) {
            Mail::to($invoice->customer->email)
                ->send(new NotifyInvoiceCreated($invoice));
        }

        return $next($invoice);
    }
}
