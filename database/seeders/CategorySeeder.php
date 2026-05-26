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
                'Akademik',
                'Inspirasi',
                'Lainnya',
            ],

            // ── Produk Buku ────────────────────────────────────────
            Category::TYPE_BOOK => [
                'Kitab',
                'Fikih',
                'Tafsir',
                'Hadits',
                'Akidah',
                'Tasawuf',
                'Sejarah Islam',
                'Bahasa Arab',
                'Pendidikan',
                'Umum',
            ],

            // ── Kegiatan / Event ───────────────────────────────────
            Category::TYPE_EVENT => [
                'Pengajian',
                'Seminar',
                'Workshop',
                'Lomba',
                'Wisuda',
                'Haul',
                'Peringatan Hari Besar',
                'Kegiatan Santri',
                'Lainnya',
            ],

            // ── Program ────────────────────────────────────────────
            Category::TYPE_PROGRAM => [
                'Tahfidz',
                'Tahsin',
                'Diniyah',
                'Terpadu',
                'Bahasa',
                'Akademik',
                'Ekstra Kurikuler',
                'Kepesantrenan',
                'Keterampilan',
                'Lainnya',
            ],

            // ── Unduhan ────────────────────────────────────────────
            Category::TYPE_DOWNLOAD => [
                'Formulir',
                'Surat Edaran',
                'Pengumuman',
                'Akademik',
                'Administrasi',
                'Kalender',
                'Lainnya',
            ],
        ];
    }
}
