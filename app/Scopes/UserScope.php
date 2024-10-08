<?php
namespace App\Scopes;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UserScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {

        $builder->where('status', UserStatusEnum::Active);

    }
}
