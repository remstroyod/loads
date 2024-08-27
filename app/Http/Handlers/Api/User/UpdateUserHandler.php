<?php
namespace App\Http\Handlers\Api\User;

use App\Http\Handlers\Api\BaseHandler;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateUserHandler extends BaseHandler
{

    public function process(Request $request, $user)
    {
        try {

            $user = User::withoutGlobalScopes()->find($user);

            $user->fill($request->all());
            $user->save();

            $this->attachUsersToManager($user, $request);

            return $user;

        } catch (\Throwable $e) {

            $this->setErrors($e->getMessage());
            return $e->getMessage();

        }
    }

    private function attachUsersToManager(User $user, Request $request)
    {

        User::withoutGlobalScopes()->where('manager_id', $user->id)->update([
            'manager_id' => NULL
        ]);

        if($request->has('users'))
        {

            $arr = collect($request->get('users'));

            User::withoutGlobalScopes()->whereIn('id', $arr->toArray())->update([
                'manager_id' => $user->id
            ]);

        }
    }

}
