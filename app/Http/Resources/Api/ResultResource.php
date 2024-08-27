<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
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
            'id'                  => $this->id,
            'offerId'             => $this->offerId,
            'offerPrice'          => $this->offerPrice,
            'roadDistanceKm'      => $this->roadDistanceKm,
            'offloading'          => ResultOffloadingResource::make($this->offloading),
            'onloading'           => ResultOnloadingResource::make($this->onloading),
            'property'            => ResultPropertyResource::make($this->property)
        ];
    }
}
