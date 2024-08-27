<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderLocationResource extends JsonResource
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
            'distances' => [
                'id'            => $this->id,
                'totalRoadKm'   => $this->totalRoadKm,
                'totalFerryKm'  => $this->totalFerryKm,
                'totalTrainKm'  => $this->totalTrainKm,
                'totalSumKm'    => $this->totalSumKm,
            ],
            'routePoints'       => OrderLocationPointResource::collection($this->points)
        ];
    }
}
