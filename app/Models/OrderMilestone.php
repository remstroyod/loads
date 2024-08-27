<?php

namespace App\Models;

use App\Enums\OfferMilestonesTypeEnum;
use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderMilestone extends Model
{
    use HasFactory, LoggableUserTrait;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'rta' => 'array'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function addres(): HasOne
    {
        return $this->hasOne(OrderMilestoneAddres::class);
    }

}
