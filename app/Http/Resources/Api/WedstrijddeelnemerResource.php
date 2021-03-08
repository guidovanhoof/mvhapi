<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WedstrijddeelnemerResource extends JsonResource
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
            'id' => $this->id,
            'wedstrijd_id' => $this->wedstrijd_id,
            'deelnemer_id' => $this->deelnemer_id,
            'is_gediskwalificeerd' => $this->is_gediskwalificeerd,
            'opmerkingen' => $this->opmerkingen,
        ];
    }
}
