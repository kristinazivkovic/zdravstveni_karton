<?php

namespace Database\Factories;

use App\Models\Pacijent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PacijentFactory extends Factory
{
    protected $model = Pacijent::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'ime' => $this->faker->firstName(),
            'prezime' => $this->faker->lastName(),
            'jmbg' => $this->faker->unique()->numerify('#############'),
            'datum_rodjenja' => $this->faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
            'pol' => $this->faker->randomElement(['muški', 'ženski']),
            'telefon' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'istorija_pacijenta' => $this->faker->paragraph(3),
        ];
    }

    public function withZdravstveniKarton()
    {
        return $this->afterCreating(function (Pacijent $pacijent) {
            $pacijent->zdravstveniKarton()->create(ZdravstveniKarton::factory()->raw([
                'user_id' => $pacijent->user_id
            ]));
        });
    }
}