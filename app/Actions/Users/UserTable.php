<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserTable
{
    public function handle(int $limit = 10): LengthAwarePaginator
    {
        return QueryBuilder::for(User::class)
            ->allowedSorts('id', 'name', 'email')
            ->allowedFilters(
                AllowedFilter::callback('search', function (Builder $query, $value): void {
                    $query
                        ->where('name', 'like', '%' . $value . '%')
                        ->orWhere('email', 'like', '%' . $value . '%');
                })
            )
            ->paginate($limit)
            ->withQueryString();
    }
}
