<?php
namespace App\Queries\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrderQuery extends Builder
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

        if($request->has('date_from'))
        {
            $this->whereDate('date_unloading', '>=', $request->get('date_from'));
        }

        if($request->has('date_to'))
        {
            $this->whereDate('date_unloading', '<=', $request->get('date_to'));
        }

        if($request->has('order_id'))
        {
            $this->where('order_id', 'LIKE', '%' . $request->get('order_id') . '%');
        }

        return $this;

    }

    public function whereIsManager(Request $request): self
    {

        $user = $request->user();

        if($user->isManager())
        {

            $this->whereHas('user', function ($query)  use ($user)
            {
                $query->where('manager_id', $user->id);

            });

        }

        return $this;
    }

}
