<?php

namespace App\Features\Automation\Services;

use App\Features\Automation\Actions\CreateLateCharge;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LateChargeAutomation
{
    public function __construct(protected CreateLateCharge $handler) {}

    public function process(?Carbon $issueAt = null, ?Order $order = null): void
    {
        if ($issueAt === null) {
            $issueAt = today();
        }

        if ($order !== null) {

            $this->handler->validate()->handle($issueAt, $order);

            return;
        }

        if (! in_array($issueAt->format('d'), $this->handler->allowedAt)) {

            return;
        }

        Order::query()
            ->withCount(['invoices' => fn (Builder $query) => $query->unresolved()])
            ->withSum(['invoices' => fn (Builder $query) => $query->unresolved()], 'unresolved_amount')
            ->whereHas('invoices', fn (Builder $query) => $query->unresolved())
            ->whereNull('completed_at')
            ->chunk(100, function (Collection $orders) use ($issueAt): void {

                foreach ($orders as $order) {

                    $this->handler->validate()->handle($issueAt, $order);
                }

            });

    }
}
