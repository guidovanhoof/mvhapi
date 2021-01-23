<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WedstrijdResource extends JsonResource
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
            'kalender_id' => $this->kalender_id,
            'datum' => $this->datum,
            'nummer' => $this->nummer,
            'omschrijving' => $this->omschrijving,
            'sponsor' => $this->sponsor,
            'aanvang' => $this->aanvang,
            'wedstrijdtype_id' => $this->wedstrijdtype_id,
            'opmerkingen' => $this->opmerkingen,
        ];
    }
}
