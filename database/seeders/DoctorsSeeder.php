<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorsSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            ['name' => 'Dr. Ahmed', 'specialty' => 'Cardiologist', 'phone' => '01000000001', 'bio' => 'Senior cardiologist.'],
            ['name' => 'Dr. Sara', 'specialty' => 'Dentist', 'phone' => '01000000002', 'bio' => 'General dentist.'],
        ];

        foreach ($doctors as $index => $d) {
            // انشاء او تحديث يوزر بسيط لكل دكتور (Doctor 1, Doctor 2, ...)
            $num = $index + 1;
            $username = 'Doctor ' . $num;
            $email = 'doctor' . $num . '@example.com';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $username,
                    'password' => Hash::make('password')
                ]
            );

            // ربط الدوكتور باليوزر الذي تم إنشاؤه/تحديثه
            Doctor::updateOrCreate(
                ['name' => $d['name']],
                array_merge($d, ['user_id' => $user->id])
            );
        }
    }
}
