<?php

namespace App\Http\Resources\Api\Admin;

use App\Http\Resources\Api\DriverResource;
use App\Http\Resources\Api\OrderFilesResource;
use App\Http\Resources\Api\OrderGoodsResource;
use App\Http\Resources\Api\OrderLocationResource;
use App\Http\Resources\Api\OrderMilestoneResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id'                    => $this->id,
            'order_id'              => $this->order_id,
            'uuid'                  => $this->uuid,
            'driver'                => DriverResource::make($this->driver),
            'assign_number_car'     => $this->assign_number_car,
            'assign_number_track'   => $this->assign_number_track,
            'date_loading'          => $this->date_loading ? $this->date_loading->format('Y-m-d H:i') : null,
            'date_unloading'        => $this->date_unloading ? $this->date_unloading->format('Y-m-d H:i') : null,
            'vehicleProperties'     => $this->vehicleProperties,
            'totalWeight'           => $this->totalWeight,
            'goods'                 => OrderGoodsResource::collection($this->goods),
            'offerPrice'            => $this->offerPrice,
            'milestones'            => OrderMilestoneResource::collection($this->milestones),
            'points'                => OrderLocationResource::make($this->locations),
            'expiredDocuments'      => $this->expiredDocuments,
            'specialEquipment'      => $this->specialEquipment,
            'specialRequirements'   => $this->specialRequirements,
            'status'                => $this->status,
            'user'                  => UserResource::make($this->user),
            'userDocuments'         => OrderFilesResource::collection($this->userDocuments),
            'adminDocuments'        => OrderFilesResource::collection($this->adminDocuments)
        ];
    }
}
