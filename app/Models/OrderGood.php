<?php

namespace App\Models;

use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderGood extends Model
{
    use HasFactory, LoggableUserTrait;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'weight' => 'array',
        'quantity' => 'array'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
