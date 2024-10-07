<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    use HasFactory;

    public const TYPE_HOME = 'home';

    public const TYPE_WORK = 'work';

    public const TYPE_DELIVERY = 'delivery';

    protected $guarded = [];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeHome()
    {
        return $this->where('type', self::TYPE_HOME);
    }
}
