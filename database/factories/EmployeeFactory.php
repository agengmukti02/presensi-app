<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pangkatGolongan = [
            ['pangkat' => 'Penata Muda', 'golongan' => 'III/a'],
            ['pangkat' => 'Penata Muda Tingkat I', 'golongan' => 'III/b'],
            ['pangkat' => 'Penata', 'golongan' => 'III/c'],
            ['pangkat' => 'Penata Tingkat I', 'golongan' => 'III/d'],
            ['pangkat' => 'Pembina', 'golongan' => 'IV/a'],
            ['pangkat' => 'Pembina Tingkat I', 'golongan' => 'IV/b'],
        ];

        $selectedPangkat = fake()->randomElement($pangkatGolongan);

        return [
            'nip' => fake()->unique()->numerify('##################'),
            'nama' => fake()->name(),
            'pangkat' => $selectedPangkat['pangkat'],
            'golongan' => $selectedPangkat['golongan'],
            'jabatan' => fake()->randomElement([
                'Kepala Dinas',
                'Sekretaris',
                'Kepala Bidang',
                'Kepala Sub Bagian',
                'Staf Administrasi',
                'Bendahara',
                'Pengadministrasi Umum',
            ]),
            'status_pegawai' => fake()->randomElement(['PNS', 'PPPK']),
            'kedudukan' => fake()->randomElement([
                'Struktural',
                'Fungsional Umum',
                'Pelaksana',
            ]),
        ];
    }
}
