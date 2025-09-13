<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentsSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::take(3)->get();
        $doctors = Doctor::take(2)->get();

        if ($patients->count() === 0 || $doctors->count() === 0) {
            return;
        }

        $appointments = [
            ['patient' => $patients[0], 'doctor' => $doctors[0], 'starts_at' => Carbon::now()->addDays(1), 'ends_at' => Carbon::now()->addDays(1)->addHour(), 'status' => 'scheduled'],
            ['patient' => $patients[1], 'doctor' => $doctors[1], 'starts_at' => Carbon::now()->addDays(2), 'ends_at' => Carbon::now()->addDays(2)->addHour(), 'status' => 'scheduled'],
        ];

        foreach ($appointments as $a) {
            Appointment::updateOrCreate(
                ['patient_id' => $a['patient']->id, 'doctor_id' => $a['doctor']->id, 'starts_at' => $a['starts_at']],
                [
                    'ends_at' => $a['ends_at'],
                    'status' => $a['status'],
                    'notes' => null,
                ]
            );
        }
    }
}
