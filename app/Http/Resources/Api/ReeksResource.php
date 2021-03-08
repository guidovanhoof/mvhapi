<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ReeksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "wedstrijd_id" => $this->wedstrijd_id,
            "nummer" => $this->nummer,
            "aanvang" => $this->aanvang,
            "duur" => $this->duur,
            "gewicht_zak" => $this->gewicht_zak,
            "opmerkingen" => $this->opmerkingen,
        ];
    }
}
