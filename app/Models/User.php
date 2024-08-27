<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserGenderEnum;
use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Enums\UserSubcontractorsEnum;
use App\Notifications\ResetPassword;
use App\Queries\Admin\UserQuery;
use App\Scopes\UserScope;
use App\Traits\LoggableUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, LoggableUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'position_id',
        'company_name',
        'street',
        'post',
        'city',
        'country_id',
        'salutation',
        'surname',
        'phone',
        'confirm_docs',
        'subcontractors',
        'gender',
        'status',
        'role',
        'manager_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'gender'            => UserGenderEnum::class,
        'subcontractors'    => UserSubcontractorsEnum::class,
        'role'              => UserRoleEnum::class,
        'status'            => UserStatusEnum::class
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::addGlobalScope(new UserScope());
    }

    /**
     * @param $query
     * @return UserQuery
     */
    public function newEloquentBuilder($query): UserQuery
    {

        return new UserQuery($query);

    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function isDashboard(): bool
    {
        return $this->role === UserRoleEnum::Admin || $this->role === UserRoleEnum::Manager;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRoleEnum::Admin;
    }

    public function isManager(): bool
    {
        return $this->role === UserRoleEnum::Manager;
    }

    public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function position(): HasOne
    {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    public function trailers(): BelongsToMany
    {
        return $this->belongsToMany(Trailer::class)->withPivot('count')->withTimestamps();
    }

    public function tractors(): BelongsToMany
    {
        return $this->belongsToMany(Tractor::class)->withPivot('count')->withTimestamps();
    }

    public function miscellaneous(): BelongsToMany
    {
        return $this->belongsToMany(Miscellaneou::class)->withPivot('count')->withTimestamps();
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function onlyUserManager(): HasMany
    {
        return $this->hasMany(self::class, 'manager_id', 'id');
    }

    public function manager(): HasOne
    {
        return $this->hasOne(self::class, 'manager_id', 'id');
    }

    public function hasRole($role): bool
    {
        $roleValue = UserRoleEnum::getRoleValueByName($role);
        return $this->role == $roleValue;
    }

}
