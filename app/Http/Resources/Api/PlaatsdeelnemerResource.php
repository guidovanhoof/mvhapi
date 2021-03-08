<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaatsdeelnemerResource extends JsonResource
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
            'plaats_id' => $this->plaats_id,
            'wedstrijddeelnemer_id' => $this->wedstrijddeelnemer_id,
            'is_weger' => $this->is_weger,
        ];
    }
}
