<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@presensi.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'id' => $admin->id,
            'nip' => '199001012015031001',
            'nama' => 'Administrator',
            'pangkat' => 'Pembina Tingkat I',
            'golongan' => 'IV/b',
            'jabatan' => 'Kepala Dinas',
            'status_pegawai' => 'PNS',
            'kedudukan' => 'Struktural',
        ]);

        // Create Sample Employees (Pegawai)
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => 'pegawai' . $i . '@presensi.com',
                'role' => 'pegawai',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);

            Employee::factory()->create([
                'id' => $user->id,
                'nama' => $user->name,
            ]);
        }
    }
}
