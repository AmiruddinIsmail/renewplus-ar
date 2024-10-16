<?php

namespace App\Actions\Invoices;

use App\Models\Invoice;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceTable
{
    public function handle(int $limit): LengthAwarePaginator
    {
        return QueryBuilder::for(Invoice::class)
            ->with('customer:id,name')
            ->withSum('payments', 'invoice_payment.amount')
            ->allowedSorts('reference_no', 'issue_at', 'due_at', 'subscription_fee', 'charge_fee', 'unresolved', 'unresolved_amount')
            ->allowedFilters(
                AllowedFilter::exact('status'),
                AllowedFilter::callback('search', function (Builder $query, $value): void {
                    $query
                        ->where('reference_no', 'like', '%' . $value . '%')
                        ->orWhere('subscription_fee', 'like', '%' . $value . '%')
                        ->orWhere('charge_fee', 'like', '%' . $value . '%')
                        ->orWhereHas('customer', function (Builder $query) use ($value) {
                            $query->where('name', 'like', '%' . $value . '%');
                        });
                })
            )
            ->paginate($limit)
            ->withQueryString();
    }
}
