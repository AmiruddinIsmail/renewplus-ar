<?php

namespace App\Actions\Payments;

use App\Actions\Contracts\WithActionTable;
use App\Models\Payment;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaymentTable implements WithActionTable
{
    /**
     * Summary of allowedSorts
     *
     * @var array
     */
    private $allowedSorts = ['reference_no', 'paid_at', 'amount', 'unresolved', 'unresolved_amount'];

    /**
     * Summary of handle
     *
     * @param  array<int|string, mixed>|null  $payload
     */
    public function handle(array $payload): LengthAwarePaginator
    {
        return QueryBuilder::for(Payment::class)
            ->with('order', 'customer', 'invoices')
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
                ->orWhere('paid_at', 'like', "%{$value}%")
                ->orWhere('amount', str_replace(['.', ',', ' '], '', $value));
        });
    }
}
