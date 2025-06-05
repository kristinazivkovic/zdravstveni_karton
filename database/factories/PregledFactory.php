<?php

namespace Database\Factories;

use App\Models\Pregled;
use App\Models\ZdravstveniKarton;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PregledFactory extends Factory
{
    protected $model = Pregled::class;

    public function definition()
    {
        return [
            'karton_id' => ZdravstveniKarton::factory(),
            'lekar_id' => User::factory()->create(['role' => 'doktor']),
            'datum' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'tip_pregleda' => $this->faker->randomElement([
                'Opšti pregled',
                'Kontrolni pregled',
                'Laboratorijski pregled',
                'Ultrazvuk',
                'EKG',
                'Rendgen'
            ]),
            'opis' => $this->faker->paragraph,
        ];
    }
}