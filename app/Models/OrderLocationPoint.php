<?php

namespace App\Models;

use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLocationPoint extends Model
{
    use HasFactory, LoggableUserTrait;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'coordinates' => 'array',
        'intermediatePoints' => 'array',
    ];

    public function location()
    {
        return $this->belongsTo(OrderLocation::class);
    }
}
