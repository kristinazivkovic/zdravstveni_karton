<?php

namespace Database\Seeders;

use App\Models\Pacijent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PacijentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        
        // Dohvati sve pacijente (korisnike sa rolom pacijent)
        $pacijentiUsers = User::where('role', 'pacijent')->get();

        foreach ($pacijentiUsers as $user) {
            Pacijent::create([
                'user_id' => $user->id,
                'ime' => $user->name,
                'prezime' => explode(' ', $user->name)[1] ?? 'Prezime',
                'jmbg' => $this->generateJMBG(),
                'datum_rodjenja' => now()->subYears(rand(18, 80))->format('Y-m-d'),
                'pol' => rand(0, 1) ? 'M' : 'Ž',
                'telefon' => '06' . rand(1000000, 9999999),
                'email' => $user->email,
                'istorija_pacijenta' => $this->generateMedicalHistory(),
            ]);
        }
    }

    private function generateJMBG()
    {
        return rand(1000000000000, 9999999999999);
    }

    private function generateMedicalHistory()
    {
        $conditions = ['Alergija na penicilin', 'Hipertenzija', 'Dijabetes', 'Astma', 'Nema poznatih bolesti'];
        $treatments = ['Terapija vitaminima', 'Redovne kontrole', 'Fizikalna terapija', 'Nema potrebe za terapijom'];
        
        return "Dijagnoze: " . $conditions[array_rand($conditions)] . "\n" .
               "Tretmani: " . $treatments[array_rand($treatments)];
    }
    
}
