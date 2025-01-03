<?php

namespace App\Models;

use App\Models\Traits\HasCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use HasCurrency, HasFactory;

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class)->latestOfMany();
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
