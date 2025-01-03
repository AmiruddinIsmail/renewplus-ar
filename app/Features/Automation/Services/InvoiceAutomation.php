<?php

namespace App\Features\Automation\Services;

use App\Features\Automation\Actions\CreateInvoice;
use App\Features\Automation\Utils\AutomationUtil;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InvoiceAutomation
{
    public function __construct(protected CreateInvoice $handler) {}

    public function process(?Carbon $issueAt = null, ?Order $order = null): void
    {
        if ($issueAt === null) {
            $issueAt = today();
        }

        if ($order !== null) {
            $this->handler->validate()->handle($issueAt, $order);

            return;
        }

        $allowedAt = AutomationUtil::invoiceCreatableAt($issueAt);

        Order::query()
            ->runnableOn($allowedAt)
            ->whereNull('completed_at')
            ->chunk(100, function (Collection $orders) use ($issueAt): void {

                foreach ($orders as $order) {

                    $this->handler->validate()->handle($issueAt, $order);

                }
            });

    }
}
