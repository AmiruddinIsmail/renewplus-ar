<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasResolver
{
    public function scopeUnresolved(Builder $query): void
    {
        $query->where('unresolved', true);
    }

    public function scopeResolved(Builder $query): void
    {
        $query->where('unresolved', false);
    }
}
