<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultOffloadingResource extends JsonResource
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
            'rtaStart'      => $this->rtaStart,
            'rtaEnd'        => $this->rtaEnd,
            'point'         => ResultOffloadingPointResource::make($this->point)
        ];
    }
}
