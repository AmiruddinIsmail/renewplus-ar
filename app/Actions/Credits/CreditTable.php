<?php

namespace App\Actions\Credits;

use App\Models\Credit;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CreditTable
{
    public function handle(int $limit = 10): LengthAwarePaginator
    {
        return QueryBuilder::for(Credit::class)
            ->with('customer:id,name')
            ->allowedSorts('id', 'reference_no', 'amount')
            ->allowedFilters(
                AllowedFilter::callback('search', function (Builder $query, $value): void {
                    $query
                        ->where('amount', 'like', '%' . $value . '%')
                        ->orWhere('reference_no', 'like', '%' . $value . '%');
                })
            )
            ->paginate($limit)
            ->withQueryString();
    }
}
