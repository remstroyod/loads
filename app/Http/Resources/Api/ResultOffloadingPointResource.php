<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultOffloadingPointResource extends JsonResource
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
            'id'                => $this->id,
            'countryCode'       => $this->country->iso,
            'city'              => $this->city,
            'zipCode'           => $this->zipCode,
            'rtaStart'          => $this->rtaStart,
            'rtaEnd'            => $this->rtaEnd,
        ];
    }
}
