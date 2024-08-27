<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ResultCollectionResource extends ResourceCollection
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
      'data' => ResultResource::collection($this->collection),
      'pagination' => [
        'total'           => $this->resource->total(),
        'perPage'         => $this->resource->perPage(),
        'currentPage'     => $this->resource->currentPage(),
        'lastPage'        => $this->resource->lastPage(),
        'nextPageUrl'     => $this->resource->nextPageUrl(),
        'previousPageUrl' => $this->resource->previousPageUrl(),
        'from'            => $this->resource->firstItem(),
        'to'              => $this->resource->lastItem()
      ],
    ];
  }

}
