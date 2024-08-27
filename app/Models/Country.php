<?php

namespace App\Models;

use App\Scopes\CountryScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new CountryScope());
    }
}
