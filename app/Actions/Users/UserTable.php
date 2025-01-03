<?php

namespace App\Actions\Users;

use App\Actions\Contracts\WithActionTable;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserTable implements WithActionTable
{
    /**
     * Summary of allowedSorts
     *
     * @var array
     */
    private $allowedSorts = ['id', 'name', 'email'];

    /**
     * Summary of handle
     *
     * @param  array<int|string, mixed>|null  $payload
     */
    public function handle(array $payload): LengthAwarePaginator
    {
        return QueryBuilder::for(User::class)
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
                ->orWhere('email', 'like', "%{$value}%");
        });
    }
}
