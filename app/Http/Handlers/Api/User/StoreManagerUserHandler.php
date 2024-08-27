<?php
namespace App\Http\Handlers\Api\User;

use App\Enums\UserRoleEnum;
use App\Http\Handlers\Api\BaseHandler;
use App\Models\User;
use Illuminate\Http\Request;

class StoreManagerUserHandler extends BaseHandler
{

    public function process(Request $request, User $user = null)
    {
        try {

            if ($user) {
                $user->fill($request->all());
                $user->save();
            } else {

                if($request->missing('role')) {
                    $request->merge(['role' => UserRoleEnum::Manager->value]);
                }

                $user = new User($request->all());
                $user->save();

                $this->attachUsersToManager($user, $request);
            }

            return $user;

        } catch (\Throwable $e) {

            $this->setErrors($e->getMessage());
            return $e->getMessage();

        }
    }

    private function attachUsersToManager(User $user, Request $request)
    {
        if($request->has('users'))
        {

            $arr = collect($request->get('users'));

            User::withoutGlobalScopes()->whereIn('id', $arr->toArray())->update([
                'manager_id' => $user->id
            ]);

        }
    }

}
