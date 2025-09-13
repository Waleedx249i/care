<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مستخدم عادي
        $user = User::create([
            'name' => 'User One',
            'email' => 'user.care.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');

        // طبيب
        $doctor = User::create([
            'name' => 'Doctor One',
            'email' => 'doctor.care.com',
            'password' => bcrypt('password'),
        ]);
        $doctor->assignRole('doctor');

        // أدمن
        $admin = User::create([
            'name' => 'Admin One',
            'email' => 'admin.care.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // موظف
        $staff = User::create([
            'name' => 'Staff One',
            'email' => 'staff.care.com',
            'password' => bcrypt('password'),
        ]);
        $staff->assignRole('staff');
    }
}
