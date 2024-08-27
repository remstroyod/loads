<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverFileResource extends JsonResource
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
            'type'          => $this->type->description(),
            'number'        => $this->number,
            'valid_from'    => $this->valid_from->format('Y-m-d'),
            'valid_until'   => $this->valid_until->format('Y-m-d'),
            'extension'     => $this->extension,
            'name'          => $this->original_name,
            'path'          => $this->path
        ];
    }
}
