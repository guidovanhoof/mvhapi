<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class KalenderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "jaar" => $this->jaar,
            "omschrijving" => $this->omschrijving(),
            "opmerkingen" => $this->opmerkingen,
        ];
    }
}
