<?php
namespace App\Queries\Admin;

use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserQuery extends Builder
{

    /**
     * @return \App\Queries\ResultQuery
     */
    public function filter(Request $request): self
    {

        if($request->has('status'))
        {
            $this->where('status', $request->get('status'));
        }

        return $this;

    }

    public function withoutAdmin(): self
    {

        $this->where('role', UserRoleEnum::User);

        return $this;

    }

    public function withoutManager(): self
    {

        $this->where('role', UserRoleEnum::User);

        return $this;

    }

    public function withoutCurrentUser(): self
    {

        $this->where('id', '!=', Auth::id());

        return $this;

    }

    public function isShowManager(): self
    {

        if(Auth::user()->isManager())
        {
            $this->where('manager_id', Auth::id());
        }

        return $this;

    }

    public function onlyUsers(): self
    {

        $this->where('role', UserRoleEnum::User);

        return $this;

    }

    public function onlyManagers(): self
    {

        $this->where('role', UserRoleEnum::Manager);

        return $this;

    }

}
