<?php

namespace App\Models;

use App\Queries\ResultQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Result extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date_parse' => 'datetime',
    ];

    /**
     * @param $query
     * @return ResultQuery
     */
    public function newEloquentBuilder($query): ResultQuery
    {

        return new ResultQuery($query);

    }

    public function onloading(): HasOne
    {
        return $this->hasOne(ResultOnloading::class);
    }

    public function offloading(): HasOne
    {
        return $this->hasOne(ResultOffloading::class);
    }

    public function property(): HasOne
    {
        return $this->hasOne(ResultProperty::class);
    }

    public function offer()
    {
        return $this->hasOne(Offer::class);
    }
}
