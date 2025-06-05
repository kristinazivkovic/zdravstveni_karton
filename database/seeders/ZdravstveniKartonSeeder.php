<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pacijent;
use App\Models\User;
use App\Models\ZdravstveniKarton;

class ZdravstveniKartonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dohvati sve pacijente
        $pacijenti = Pacijent::all();
        
        // Dohvati doktore
        $lekari = User::where('role', 'doktor')->get();

        foreach ($pacijenti as $pacijent) {
            ZdravstveniKarton::create([
                'pacijent_id' => $pacijent->id,
                'user_id' => $lekari->random()->id,
                'visina' => rand(150, 200),
                'tezina' => rand(50, 120),
                'krvni_pritisak' => rand(100, 140) . '/' . rand(60, 90),
                'dijagnoza' => $this->generateRandomDiagnosis(),
                'tretman' => $this->generateRandomTreatment(),
            ]);
        }
    }

    private function generateRandomDiagnosis()
    {
        $diagnoses = [
            'Hipertenzija',
            'Dijabetes tip 2',
            'Hiperholesterolemija',
            'Astma',
            'Kronična opstruktivna bolest pluća',
            'Zdrav'
        ];
        
        return $diagnoses[array_rand($diagnoses)];
    }

    private function generateRandomTreatment()
    {
        $treatments = [
            'Redovno merenje krvnog pritiska',
            'Dijeta i vežbanje',
            'Medikamentna terapija',
            'Redovne kontrole',
            'Nema potrebe za tretmanom'
        ];
        
        return $treatments[array_rand($treatments)];
    }
    
}
