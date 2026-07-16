<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaults = $this->defaults();

        foreach ($defaults as $type => $names) {
            foreach ($names as $order => $name) {
                Category::firstOrCreate(
                    ['type' => $type, 'name' => $name],
                    ['sort_order' => $order + 1, 'is_active' => true],
                );
            }
        }
    }

    /** @return array<string, string[]> */
    private function defaults(): array
    {
        return [
            // ── Blog / Berita ─────────────────────────────────────
            Category::TYPE_POST => [
                'Berita',
                'Pengumuman',
                'Prestasi',
                'Kegiatan',
                'Tips & Panduan',
                'Inspirasi',
                'Lainnya',
            ],

            // ── Produk / Katalog ───────────────────────────────────
            Category::TYPE_BOOK => [
                'Teknologi',
                'Bisnis',
                'Desain',
                'Manajemen',
                'Fotografi',
                'Pengembangan Diri',
                'Umum',
            ],

            // ── Kegiatan / Event ───────────────────────────────────
            Category::TYPE_EVENT => [
                'Seminar',
                'Workshop',
                'Webinar',
                'Lomba',
                'Pertemuan',
                'Peringatan Hari Besar',
                'Bakti Sosial',
                'Lainnya',
            ],

            // ── Program / Fitur ────────────────────────────────────
            Category::TYPE_PROGRAM => [
                'Unggulan',
                'Fitur',
                'Layanan',
                'Lainnya',
            ],

            // ── Unduhan ────────────────────────────────────────────
            Category::TYPE_DOWNLOAD => [
                'Formulir',
                'Surat Edaran',
                'Pengumuman',
                'Panduan',
                'Administrasi',
                'Kalender',
                'Lainnya',
            ],
        ];
    }
}
