<?php

namespace App\Models;

use App\Enums\UserDriverDocumentTypeEnum;
use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverFile extends Model
{
    use HasFactory, LoggableUserTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'type'          => UserDriverDocumentTypeEnum::class,
        'valid_from'    => 'date',
        'valid_until'   => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

}
