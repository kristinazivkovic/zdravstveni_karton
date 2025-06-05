<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PregledResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'datum' => $this->datum,
            'tip_pregleda' => $this->tip_pregleda,
            'opis' => $this->opis,
            'lekar' => new UserResource($this->whenLoaded('lekar')),
            'karton' => new ZdravstveniKartonResource($this->whenLoaded('karton')),
        ];
    }
}