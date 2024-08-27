<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderMilestoneResource extends JsonResource
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
            'id'            => $this->id,
            'milestoneId'   => $this->milestoneId,
            'type'          => $this->type,
            'rta'           => $this->rta,
            'address'       => OrderMilestoneAddresResource::make($this->addres)
        ];
    }
}
