<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
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
            'gender'        => $this->gender,
            'first_name'    => $this->first_name,
            'surname'       => $this->surname,
            'phone'         => $this->phone,
            'email'         => $this->email,
            'has_license'   => $this->has_license,
            'languages'     => LanguageResource::collection($this->languages),
            'file'          => DriverFileResource::make($this->files)
        ];
    }
}
