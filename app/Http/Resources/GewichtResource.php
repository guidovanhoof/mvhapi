<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GewichtResource extends JsonResource
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
            "plaats_id" => $this->plaats_id,
            "gewicht" => $this->gewicht,
            "is_geldig" => $this->is_geldig,
        ];
    }
}
