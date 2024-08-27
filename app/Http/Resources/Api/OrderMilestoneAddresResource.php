<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderMilestoneAddresResource extends JsonResource
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
            'countryCode'   => $this->countryCode,
            'zipCode'       => $this->zipCode,
            'city'          => $this->city,
            'loadingTimes'  => OrderMilestoneAddresTimeResource::make($this->time)
        ];
    }
}
