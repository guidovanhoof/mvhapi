<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaatsResource extends JsonResource
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
            "reeks_id" => $this->reeks_id,
            "nummer" => $this->nummer,
            "opmerkingen" => $this->opmerkingen,
        ];
    }
}
