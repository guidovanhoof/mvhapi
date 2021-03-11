<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetrokkenMaatResource extends JsonResource
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
            'id' => $this->id,
            'wedstrijddeelnemer_id' => $this->wedstrijddeelnemer_id,
            'getrokken_maat_id' => $this->getrokken_maat_id,
        ];
    }
}
