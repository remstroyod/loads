<?php

namespace App\Models;

use App\Enums\FileEnum;
use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory, LoggableUserTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => FileEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withoutGlobalScopes();
    }
}
