<?php
namespace App\Http\Handlers\Api\Order;

use App\Http\Handlers\Api\BaseHandler;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateUserHandler extends BaseHandler
{

    public function process(Request $request, User $user)
    {
        try {

            $user->fill($request->all());
            $user->save();

            return $user;

        } catch (\Throwable $e) {

            $this->setErrors($e->getMessage());
            return $e->getMessage();

        }
    }

}
