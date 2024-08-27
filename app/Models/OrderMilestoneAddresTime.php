<?php

namespace App\Models;

use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMilestoneAddresTime extends Model
{
    use HasFactory, LoggableUserTrait;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'onloading' => 'array',
        'offloading' => 'array',
    ];

    public function milestoneAddres(): BelongsTo
    {
        return $this->belongsTo(OrderMilestoneAddres::class);
    }
}
