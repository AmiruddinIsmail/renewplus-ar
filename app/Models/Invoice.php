<?php

namespace App\Models;

use App\Models\Enums\InvoiceStatus;
use App\Models\Traits\HasCurrency;
use App\Models\Traits\HasResolver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;

class Invoice extends Model
{
    use HasCurrency, HasFactory, HasResolver;

    public const PREFIX = 'INV';

    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i A',
        'status' => InvoiceStatus::class,
    ];

    public static function runnableOn(Carbon $date, string $paymentDriver): Collection
    {
        return self::query()
            ->withWhereHas('order', function ($builder) use ($paymentDriver): void {
                $builder->where('payment_gateway', $paymentDriver)->select('id', 'reference_no', 'payment_reference');
            })
            ->whereHas('transaction', function ($builder): void {
                $builder->whereNull('gateway_status')->whereNull('gateway_id');
            })
            ->whereDate('issue_at', $date->format('Y-m-d'))
            ->unresolved()
            ->get();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class)->withPivot('amount');
    }

    public function credits(): BelongsToMany
    {
        return $this->belongsToMany(Credit::class)->withPivot('amount', 'created_at');
    }

    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s')
        );
    }
}
