<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class)->withPivot('amount');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    // --------------------- scopes ----------------------

    public function scopeUnresolved(): void
    {
        $this->where('unresolved', true);
    }

    public function scopeResolved(): void
    {
        $this->where('unresolved', false);
    }
}
