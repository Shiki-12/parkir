<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    public function run(): void
    {
        $locations = [
            [
                'location_name' => 'Gedung A',
                'max_motorcycle' => 3,
                'max_car' => 0,
                'max_other' => 0,
            ],
            [
                'location_name' => 'Gedung B',
                'max_motorcycle' => 3,
                'max_car' => 3,
                'max_other' => 0,
            ],
            [
                'location_name' => 'Gedung C',
                'max_motorcycle' => 3,
                'max_car' => 3,
                'max_other' => 3,
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }

        $vehicleTypes = [
            [
                'jenis' => 'motorcycle',
                'perjam_pertama' => 2000,
                'perjam_berikutnya' => 1000,
                'max_perhari' => 10000,
            ],
            [
                'jenis' => 'car',
                'perjam_pertama' => 3000,
                'perjam_berikutnya' => 2000,
                'max_perhari' => 15000,
            ],
            [
                'jenis' => 'other',
                'perjam_pertama' => 5000,
                'perjam_berikutnya' => 3000,
                'max_perhari' => 30000,
            ],
        ];

        foreach ($vehicleTypes as $vehicleType) {
            VehicleType::create($vehicleType);
        }
    }
}
