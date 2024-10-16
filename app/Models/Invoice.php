<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Invoice extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_OVERDUE = 'overdue';

    public const STATUS_PAID = 'paid';

    public const STATUS_PARTIAL_PAID = 'partial';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i A',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
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

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function scopeUnresolved(Builder $query): void
    {
        $query->where('unresolved', true);
    }

    public function scopeResolved(Builder $query): void
    {
        $query->where('unresolved', false);
    }

    public function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s')
        );
    }
}
