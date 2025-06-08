<?php

namespace Database\Seeders;

use App\Models\Pacijent;
use App\Models\User;
use App\Models\ZdravstveniKarton;
use Illuminate\Database\Seeder;

class ZdravstveniKartonSeeder extends Seeder
{
    public function run()
    {

        $krvneGrupe = ZdravstveniKarton::$krvneGrupe;
        // Kreiraj kartone samo ako ne postoje
        if (ZdravstveniKarton::count() === 0) {
            $pacijenti = Pacijent::all();
            $lekari = User::where('role', 'doktor')->get();

            foreach ($pacijenti as $pacijent) {
                $lekar = $lekari->random();
                
                ZdravstveniKarton::create([
                    'pacijent_id' => $pacijent->id,
                    'user_id' => $lekar->id,
                    'visina' => $this->generateHeight($pacijent->pol),
                    'tezina' => $this->generateWeight($pacijent->pol),
                    'krvna_grupa' => $krvneGrupe[array_rand($krvneGrupe)],
                    'krvni_pritisak' => $this->generateBloodPressure(),
                    'dijagnoza' => $this->generateRandomDiagnosis($lekar->name),
                    'tretman' => $this->generateRandomTreatment($lekar->name),
                ]);
            }
        }else {
            // Ako već postoje kartoni, samo ažuriraj krvnu grupu
            ZdravstveniKarton::whereNull('krvna_grupa')
                ->get()
                ->each(function ($karton) use ($krvneGrupe) {
                    $karton->update([
                        'krvna_grupa' => $krvneGrupe[array_rand($krvneGrupe)]
                    ]);
                });
        }
    }

    private function generateHeight($gender)
    {
        return $gender === 'muški' ? rand(165, 200) : rand(150, 185);
    }

    private function generateWeight($gender)
    {
        return $gender === 'muški' ? rand(60, 120) : rand(50, 90);
    }

    private function generateBloodPressure()
    {
        $systolic = rand(100, 140);
        $diastolic = rand(60, 90);
        return "$systolic/$diastolic";
    }

    private function generateRandomDiagnosis($doctorName)
    {
        $diagnoses = [
            "Hipertenzija (utvrdio dr. $doctorName)",
            "Dijabetes tip 2",
            "Hiperholesterolemija",
            "Astma",
            "KOPB",
            "Zdrav - potvrđeno " . now()->format('Y'),
            "Migrena",
            "Artritis"
        ];
        
        return $diagnoses[array_rand($diagnoses)];
    }

    private function generateRandomTreatment($doctorName)
    {
        $treatments = [
            "Redovne kontrole kod dr. $doctorName",
            "Terapija: " . $this->generateMedication(),
            "Dijeta i vežbanje",
            "Fizikalna terapija",
            "Hitna intervencija po potrebi",
            "Kontrola za 3 meseca",
            "Nema potrebe za tretmanom"
        ];
        
        return $treatments[array_rand($treatments)];
    }

    private function generateMedication()
    {
        $meds = [
            "Ambroxol 30mg 1x1",
            "Brufen 400mg po potrebi",
            "Lozartan 50mg 1x1",
            "Metformin 500mg 2x1",
            "Ventolin inhalacija"
        ];
        return $meds[array_rand($meds)];
    }
}