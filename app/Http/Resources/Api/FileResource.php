<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'type'              => $this->type->description(),
            'original_name'     => $this->original_name,
            'extension'         => $this->extension,
            'name'              => $this->name,
            'path'              => $this->path
        ];
    }
}
