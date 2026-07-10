<?php

namespace Database\Seeders;

use App\Models\AdmissionPath;
use Illuminate\Database\Seeder;

class AdmissionPathSeeder extends Seeder
{
    public function run(): void
    {
        $paths = [
            ['slug' => 'zonasi', 'name' => 'Zonasi', 'icon' => '🏡', 'color' => 'info', 'description' => 'Berdasarkan jarak domisili ke sekolah.', 'sort_order' => 1],
            ['slug' => 'prestasi', 'name' => 'Prestasi', 'icon' => '🏆', 'color' => 'success', 'description' => 'Berdasarkan nilai rapor atau prestasi akademik/non-akademik.', 'sort_order' => 2],
            ['slug' => 'afirmasi', 'name' => 'Afirmasi', 'icon' => '💚', 'color' => 'warning', 'description' => 'Untuk peserta didik dari keluarga tidak mampu.', 'sort_order' => 3],
            ['slug' => 'mutasi', 'name' => 'Mutasi', 'icon' => '🔄', 'color' => 'gray', 'description' => 'Untuk anak guru/tenaga kependidikan atau pindah tugas orang tua.', 'sort_order' => 4],
        ];

        foreach ($paths as $path) {
            AdmissionPath::updateOrCreate(
                ['slug' => $path['slug']],
                array_merge($path, ['is_active' => true]),
            );
        }
    }
}
