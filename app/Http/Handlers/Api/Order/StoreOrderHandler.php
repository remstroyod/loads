<?php
namespace App\Http\Handlers\Api\Order;

use App\Http\Handlers\Api\BaseHandler;
use App\Models\Order;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreOrderHandler extends BaseHandler
{

    use FileTrait;

    public function process(Request $request, Order $order = null)
    {
        try {

            if ($order) {
                $order->fill($request->all());
                $order->save();
            } else {
                $order = Auth::user()->orders()->create($request->all());

                $this->goods($request, $order);
                $this->milestones($request, $order);
                $this->locations($request, $order);

            }

            return $order;

        } catch (\Throwable $e) {

            $this->setErrors($e->getMessage());
            return $e->getMessage();

        }
    }

    private function goods(Request $request, Order $order)
    {
        if($request->has('goods')) {
            $goods = $request->get('goods');
            foreach ($goods as $good) {
                $order->goods()->create($good);
            }
        }
    }

    private function milestones(Request $request, Order $order)
    {
        if($request->has('milestones')) {
            $milestones = $request->get('milestones');
            foreach ($milestones as $milestone) {
                $item = $order->milestones()->create($milestone);
                $address = $item->addres()->create($milestone['address']);
                $address->time()->create($milestone['address']['loadingTimes']);
            }
        }
    }

    private function locations(Request $request, Order $order)
    {
        if($request->has('points')) {
            $points = $request->get('points');
            $loc = $order->locations()->create($points['distances']);

            foreach ($points['routePoints'] as $point) {

                $loc->points()->create($point);
            }
        }
    }

}
