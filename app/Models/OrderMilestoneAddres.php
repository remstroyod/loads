<?php

namespace App\Models;

use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderMilestoneAddres extends Model
{
    use HasFactory, LoggableUserTrait;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(OrderMilestone::class);
    }

    public function time(): HasOne
    {
        return $this->hasOne(OrderMilestoneAddresTime::class);
    }
}
