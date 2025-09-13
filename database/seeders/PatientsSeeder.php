<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use Carbon\Carbon;

class PatientsSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['code' => 'P001', 'name' => 'Ali Hassan', 'gender' => 'male', 'phone' => '0111000001', 'birth_date' => '1985-05-10', 'address' => '123 Street', 'notes' => null],
            ['code' => 'P002', 'name' => 'Mona Khaled', 'gender' => 'female', 'phone' => '0111000002', 'birth_date' => '1990-08-20', 'address' => '456 Avenue', 'notes' => null],
            ['code' => 'P003', 'name' => 'Omar Ali', 'gender' => 'male', 'phone' => '0111000003', 'birth_date' => '2000-01-15', 'address' => '789 Road', 'notes' => null],
        ];

        foreach ($patients as $p) {
            Patient::updateOrCreate(
                ['code' => $p['code']],
                $p
            );
        }
    }
}
