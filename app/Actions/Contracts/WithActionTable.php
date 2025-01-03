<?php

namespace App\Actions\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WithActionTable
{
    /**
     * Summary of handle
     */
    public function handle(array $payload): LengthAwarePaginator;
}
