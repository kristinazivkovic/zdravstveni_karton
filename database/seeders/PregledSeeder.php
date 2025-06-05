<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use App\Models\User;
use App\Models\Pregled;
use App\Models\ZdravstveniKarton;
use Carbon\Carbon;

class PregledSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   
        public function run()
        {
            $kartoni = ZdravstveniKarton::all();
    
            foreach ($kartoni as $karton) {
                // Kreiraj 1-5 pregleda za svaki karton
                $brojPregleda = rand(1, 5);
                
                for ($i = 0; $i < $brojPregleda; $i++) {
                    Pregled::create([
                        'karton_id' => $karton->id,
                        'lekar_id' => $karton->user_id,
                        'datum' => Carbon::now()->subDays(rand(1, 365))->format('Y-m-d H:i:s'),
                        'tip_pregleda' => $this->getRandomExamType(),
                        'opis' => $this->generateExamDescription(),
                    ]);
                }
            }
        }
    
        private function getRandomExamType()
        {
            $types = [
                'Opšti pregled',
                'Kontrolni pregled',
                'Laboratorijski pregled',
                'Ultrazvuk',
                'EKG',
                'Rendgen'
            ];
            
            return $types[array_rand($types)];
        }
    
        private function generateExamDescription()
        {
            $descriptions = [
                'Pacijent se žali na umor i malaksalost.',
                'Redovna kontrola tokom terapije.',
                'Pacijent bez tegoba, stanje zadovoljavajuće.',
                'Uočena blaga hipertenzija, preporučena dijeta.',
                'Potrebna dodatna dijagnostika.',
                'Pacijent stabilan, terapija daje rezultate.'
            ];
            
            return $descriptions[array_rand($descriptions)];
        }
    }

