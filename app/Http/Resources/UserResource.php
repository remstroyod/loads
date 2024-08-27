<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\CountryResource;
use App\Http\Resources\Api\FileResource;
use App\Http\Resources\Api\MiscellaneouResource;
use App\Http\Resources\Api\PositionResource;
use App\Http\Resources\Api\TractorResource;
use App\Http\Resources\Api\TrailerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {

        return [
            'id'                => $this->id,
            'manager'           => $this->manager_id,
            'name'              => $this->name,
            'surname'           => $this->surname,
            'email'             => $this->email,
            'position'          => PositionResource::make($this->position),
            'gender'            => $this->gender,
            'company_name'      => $this->company_name,
            'street'            => $this->street,
            'post'              => $this->post,
            'city'              => $this->city,
            'country'           => CountryResource::make($this->country),
            'salutation'        => $this->salutation,
            'phone'             => $this->phone,
            'confirm_docs'      => $this->confirm_docs,
            'subcontractors'    => $this->subcontractors,
            'trailers'          => TrailerResource::collection($this->trailers),
            'tractors'          => TractorResource::collection($this->tractors),
            'miscellaneous'     => MiscellaneouResource::collection($this->miscellaneous),
            'files'             => FileResource::collection($this->files),
            'isAdmin'           => $this->isAdmin(),
            'isManager'         => $this->isManager(),
            'status'            => $this->status,
            'orders_count'      => $this->orders->count()
        ];
    }
}
