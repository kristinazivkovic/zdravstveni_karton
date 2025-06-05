<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PacijentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ime' => $this->ime,
            'prezime' => $this->prezime,
            'jmbg' => $this->jmbg,
            'datum_rodjenja' => $this->datum_rodjenja,
            'pol' => $this->pol,
            'telefon' => $this->telefon,
            'email' => $this->email,
            'istorija_pacijenta' => $this->istorija_pacijenta,
            'user' => new UserResource($this->whenLoaded('user')),
            'zdravstveni_karton' => new ZdravstveniKartonResource($this->whenLoaded('zdravstveniKarton')),
        ];
    }
}