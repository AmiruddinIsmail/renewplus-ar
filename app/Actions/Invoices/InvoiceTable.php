<?php

namespace App\Actions\Invoices;

use App\Actions\Contracts\WithActionTable;
use App\Models\Invoice;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InvoiceTable implements WithActionTable
{
    /**
     * Summary of allowedSorts
     *
     * @var array
     */
    private $allowedSorts = ['reference_no', 'issue_at', 'due_at', 'subscription_amount', 'charge_amount', 'credit_paid', 'status'];

    /**
     * Summary of handle
     *
     * @param  array<int|string, mixed>|null  $payload
     */
    public function handle(array $payload): LengthAwarePaginator
    {
        return QueryBuilder::for(Invoice::class)
            ->with('order', 'customer', 'charges', 'payments')
            ->allowedSorts($this->allowedSorts)
            ->allowedFilters([$this->globalFilter()])
            ->when(isset($payload['customer_id']), function (Builder $query) use ($payload): void {
                $query->where('customer_id', $payload['customer_id']);
            })
            ->paginate($payload['limit'] ?? 10)
            ->withQueryString();
    }

    /**
     * Summary of globalFilter
     */
    private function globalFilter(): AllowedFilter
    {
        return AllowedFilter::callback('search', function (Builder $query, $value): void {
            $query
                ->where('reference_no', 'like', "%{$value}%")
                ->orWhere('issue_at', 'like', "%{$value}%")
                ->orWhere('status', str_replace(['.', ',', ' '], '', $value));
        });
    }
}
