<?php

namespace App\Models;

use App\Queries\Admin\OrderQuery;
use App\Scopes\OrderByScope;
use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes, LoggableUserTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'vehicleProperties'     => 'array',
        'totalWeight'           => 'array',
        'date_loading'          => 'datetime',
        'date_unloading'        => 'datetime',
        'specialRequirements'   => 'array',
        'specialEquipment'      => 'array',
        'expiredDocuments'      => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new OrderByScope());
    }

    /**
     * @param $query
     * @return OrderQuery
     */
    public function newEloquentBuilder($query): OrderQuery
    {

        return new OrderQuery($query);

    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withoutGlobalScopes();
    }

    public function goods(): HasMany
    {
        return $this->hasMany(OrderGood::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(OrderMilestone::class);
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }

    public function locations(): HasOne
    {
        return $this->hasOne(OrderLocation::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OrderDocument::class);
    }

    public function userDocuments(): HasMany
    {
        return $this->hasMany(OrderDocument::class)->where('type', 1);
    }

    public function adminDocuments(): HasMany
    {
        return $this->hasMany(OrderDocument::class)->where('type', 2);
    }
}
