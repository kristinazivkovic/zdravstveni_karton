<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZdravstveniKartonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'visina' => $this->visina,
            'tezina' => $this->tezina,
            'krvni_pritisak' => $this->krvni_pritisak,
            'dijagnoza' => $this->dijagnoza,
            'tretman' => $this->tretman,
            'lekar' => [
                'id' => $this->lekar->id,
                'ime' => $this->lekar->name,
            ],
            'pacijent' => [
                'id' => $this->pacijent->id,
                'ime' => $this->pacijent->ime,
            ]
        ];
    }
}
