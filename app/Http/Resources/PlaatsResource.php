<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "reeks_id" => $this->reeks_id,
            "nummer" => $this->nummer,
            "opmerkingen" => $this->opmerkingen,
        ];
    }
}
