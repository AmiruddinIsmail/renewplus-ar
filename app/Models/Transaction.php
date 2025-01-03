<?php

namespace App\Models;

use App\Models\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasCurrency, HasFactory;

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}
