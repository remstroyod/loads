<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultProperty extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = false;

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }
}
