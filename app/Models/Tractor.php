<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tractor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withoutGlobalScopes()->withPivot('count')->withTimestamps();
    }
}
