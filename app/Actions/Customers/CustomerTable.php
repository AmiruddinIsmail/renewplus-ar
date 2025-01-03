<?php

namespace App\Actions\Customers;

use App\Actions\Contracts\WithActionTable;
use App\Models\Customer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CustomerTable implements WithActionTable
{
    /**
     * Summary of allowedSorts
     *
     * @var array
     */
    private $allowedSorts = ['uuid', 'name', 'email', 'phone', 'tenure', 'contract_at', 'subscription_amount'];

    /**
     * Summary of handle
     *
     * @param  array<int|string, mixed>|null  $payload
     */
    public function handle(?array $payload): LengthAwarePaginator
    {
        return QueryBuilder::for(Customer::class)
            ->withWhereHas('order', function ($builder) {
                $builder->where('active', true)->orderBy('id', 'desc');
            })
            ->withSum([
                'invoices' => function ($builder): void {
                    $builder->where('unresolved', true);
                },
            ], 'unresolved_amount')
            ->allowedSorts($this->allowedSorts)
            ->allowedFilters([$this->globalFilter()])
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
                ->where('name', 'like', "%{$value}%")
                ->orWhere('uuid', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%")
                ->orWhere('status', 'like', "%{$value}%")
                ->orWhere('created_at', 'like', "%{$value}%");
        });
    }
}
