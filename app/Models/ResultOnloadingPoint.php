<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ResultOnloadingPoint extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'rtaStart' => 'datetime',
        'rtaEnd' => 'datetime',
    ];

    public function onloading(): BelongsTo
    {
        return $this->belongsTo(ResultOnloading::class);
    }

    public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'countryCode');
    }
}
