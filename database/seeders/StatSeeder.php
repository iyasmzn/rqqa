<?php

namespace Database\Seeders;

use App\Models\Stat;
use Illuminate\Database\Seeder;

class StatSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['icon' => '🕌', 'label' => 'Berdiri Sejak',   'value' => '1992',  'sub' => 'Lebih dari 3 dekade',     'sort_order' => 1],
            ['icon' => '📖', 'label' => 'Total Santri',    'value' => '850',   'sub' => 'Mukim & non-mukim',       'sort_order' => 2],
            ['icon' => '👳', 'label' => 'Asatidz',         'value' => '60+',   'sub' => 'Hafidz & alumni pesantren', 'sort_order' => 3],
            ['icon' => '🏆', 'label' => 'Hafidz Qur\'an',  'value' => '200+',  'sub' => 'Alumni 30 juz',           'sort_order' => 4],
        ];

        foreach ($defaults as $data) {
            Stat::firstOrCreate(['label' => $data['label']], $data);
        }
    }
}
