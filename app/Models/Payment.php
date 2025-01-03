<?php

namespace App\Models;

use App\Models\Traits\HasCurrency;
use App\Models\Traits\HasResolver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Payment extends Model
{
    use HasCurrency, HasFactory, HasResolver;

    public const PREFIX = 'PAY';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class)->withPivot('amount');
    }

    public function transaction(): MorphOne
    {
        return $this->MorphOne(Transaction::class, 'transactionable');
    }
}
