<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    public function run(): void
    {
        if (Slide::exists()) {
            return;
        }

        $defaults = [
            [
                'title' => 'Selamat Datang di Pondok Pesantren',
                'subtitle' => 'Pondok pesantren modern yang membentuk generasi Qurani berilmu, berakhlak mulia, dan siap menghadapi tantangan zaman.',
                'button_label' => 'Profil Pesantren',
                'button_url' => '#sambutan',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Program Tahfidz Al-Qur\'an',
                'subtitle' => 'Raih hafalan 30 juz dengan metode terbukti, dibimbing langsung oleh para asatidz berpengalaman dan hafidz Qur\'an.',
                'button_label' => 'Lihat Program',
                'button_url' => '/program',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Penerimaan Santri Baru 2026/2027',
                'subtitle' => 'Pendaftaran resmi dibuka. Tersedia program Tahfidz, Diniyah, dan Terpadu. Daftarkan putra-putri Anda sekarang.',
                'button_label' => 'Daftar Sekarang',
                'button_url' => '/ppdb',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($defaults as $data) {
            Slide::create($data);
        }
    }
}
