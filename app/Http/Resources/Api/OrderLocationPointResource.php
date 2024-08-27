<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderLocationPointResource extends JsonResource
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
            'type'                  => $this->type,
            'coordinates'           => $this->coordinates,
            'intermediatePoints'    => $this->intermediatePoints,
            'loadingPointNumber'    => $this->loadingPointNumber,
            'milestoneId'           => $this->milestoneId
        ];
    }
}
