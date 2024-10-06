<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'contract_at' => 'datetime:d/m/Y h:i A',
    ];

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }
}
