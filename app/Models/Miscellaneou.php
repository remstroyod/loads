<?php

namespace App\Models;

use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Miscellaneou extends Model
{
    use HasFactory, LoggableUserTrait;

    protected $guarded = ['id'];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withoutGlobalScopes()->withPivot('count')->withTimestamps();
    }
}
