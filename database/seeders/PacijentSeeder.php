<?php

namespace Database\Seeders;

use App\Models\Pacijent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PacijentSeeder extends Seeder
{
    public function run()
    {
        // Kreiraj 10 pacijenata ako već ne postoje
        if (Pacijent::count() === 0) {
            $lekari = User::where('role', 'doktor')->get();
            
            for ($i = 0; $i < 10; $i++) {
                $ime = $this->generateFirstName();
                $prezime = $this->generateLastName();
                $email = Str::lower($ime . '.' . $prezime . '@example.com');
                
                $user = User::create([
                    'name' => $ime . ' ' . $prezime,
                    'email' => $email,
                    'password' => bcrypt('password'),
                    'role' => 'pacijent',
                ]);

                Pacijent::create([
                    'user_id' => $user->id,
                    'ime' => $ime,
                    'prezime' => $prezime,
                    'jmbg' => $this->generateJMBG(),
                    'datum_rodjenja' => $this->generateBirthDate(),
                    'pol' => $this->generateGender(),
                    'telefon' => $this->generatePhoneNumber(),
                    'email' => $email,
                    'istorija_pacijenta' => $this->generateMedicalHistory($lekari->random()->id),
                ]);
            }
        }
    }

    private function generateFirstName()
    {
        $names = ['Ana', 'Marko', 'Jovana', 'Nikola', 'Milica', 'Stefan', 'Sofija', 'Luka', 'Ema', 'Vuk'];
        return $names[array_rand($names)];
    }

    private function generateLastName()
    {
        $lastNames = ['Petrović', 'Jovanović', 'Nikolić', 'Marković', 'Đorđević', 'Stojanović', 'Ilić', 'Pavlović'];
        return $lastNames[array_rand($lastNames)];
    }

    private function generateJMBG()
    {
        return rand(1000000000000, 9999999999999);
    }

    private function generateBirthDate()
    {
        return now()->subYears(rand(18, 80))->subDays(rand(0, 365))->format('Y-m-d');
    }

    private function generateGender()
    {
        return rand(0, 1) ? 'muški' : 'ženski';
    }

    private function generatePhoneNumber()
    {
        return '06' . rand(10, 99) . '-' . rand(100, 999) . '-' . rand(100, 999);
    }

    private function generateMedicalHistory($lekarId)
    {
        $conditions = [
            'Alergija na penicilin',
            'Hipertenzija',
            'Dijabetes tip 2',
            'Astma',
            'Povijest srčanih problema',
            'Nema poznatih bolesti'
        ];
        
        $treatments = [
            'Terapija vitaminima',
            'Redovne kontrole kod lekara ID: ' . $lekarId,
            'Fizikalna terapija',
            'Antihipertenzivna terapija',
            'Inhalatori',
            'Nema potrebe za terapijom'
        ];
        
        return "Dijagnoze: " . $conditions[array_rand($conditions)] . "\n" .
               "Tretmani: " . $treatments[array_rand($treatments)] . "\n" .
               "Poslednji pregled: " . now()->subMonths(rand(1, 12))->format('Y-m-d');
    }
}