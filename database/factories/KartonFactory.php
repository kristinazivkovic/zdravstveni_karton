<?php

namespace Database\Factories;

use App\Models\ZdravstveniKarton;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZdravstveniKartonFactory extends Factory
{
    protected $model = ZdravstveniKarton::class;

    public function definition()
    {
        return [
            'pacijent_id' => \App\Models\Pacijent::factory(),
            'user_id' => User::factory(),
            'visina' => $this->faker->numberBetween(150, 200),
            'tezina' => $this->faker->numberBetween(50, 120),
            'krvni_pritisak' => $this->faker->numerify('###/##'),
            'dijagnoza' => $this->faker->randomElement([null, 'Hipertenzija', 'Dijabetes tip 2', 'Astma', 'Migrena']),
            'tretman' => $this->faker->randomElement([null, 'Lekovi', 'Fizikalna terapija', 'Dijeta', 'Redovni pregledi']),
        ];
    }

    public function withPregledi($count = 3)
    {
        return $this->afterCreating(function (ZdravstveniKarton $karton) use ($count) {
            $karton->pregledi()->createMany(
                \App\Models\Pregled::factory()->count($count)->make()->toArray()
            );
        });
    }
}