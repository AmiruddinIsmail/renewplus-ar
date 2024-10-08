<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Charge extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const TYPE_LATE = 'late';

    public const TYPE_PENALTY = 'penalty';

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
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

    // ----------------- helpers function --------------
    public static function isLateChargeable(int $unresolvedInvoiceAmount, Carbon $invoiceDate, Carbon $lateChargeDate, int $unresolvedInvoiceCount = 0): bool
    {

        if ($invoiceDate->gt($lateChargeDate)) {
            return false;
        }

        if ($unresolvedInvoiceCount >= 2) {
            return true;
        }

        if ($unresolvedInvoiceAmount > 0) {
            if ($invoiceDate->diffInDays($lateChargeDate) > 8) {
                return true;
            }

            return false;
        }

        if ($invoiceDate->diffInDays($lateChargeDate) > 8) {
            return true;
        }

        return false;
    }

    public static function referenceNoConvention(int $runningNo, Carbon $today): string
    {
        return 'LATE-'.$today->format('Ymd').'-'.str_pad($runningNo, 4, '0', STR_PAD_LEFT);
    }
}
