<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class WedstrijddeelnemerJeugdcategorieResource extends JsonResource
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
            'wedstrijddeelnemer_id' => $this->wedstrijddeelnemer_id,
            'jeugdcategorie_id' => $this->jeugdcategorie_id,
        ];
    }
}
