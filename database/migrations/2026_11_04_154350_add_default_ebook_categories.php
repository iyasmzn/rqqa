<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Kategori bawaan e-book. Situs yang sudah berjalan tidak menjalankan ulang
     * CategorySeeder (yang ikut menghidupkan lagi kategori tipe lain yang sengaja
     * dihapus admin), jadi daftar ini dipasang lewat migrasi — tetap sama dengan
     * daftar e-book di CategorySeeder.
     *
     * @var list<string>
     */
    private const NAMES = [
        'Buku Pelajaran',
        'Modul',
        'Kitab',
        'Referensi',
        'Bacaan Umum',
        'Lainnya',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();

        DB::table('categories')->insertOrIgnore(array_map(
            fn (string $name, int $index): array => [
                'type' => 'ebook',
                'name' => $name,
                'sort_order' => $index + 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            self::NAMES,
            array_keys(self::NAMES),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('categories')
            ->where('type', 'ebook')
            ->whereIn('name', self::NAMES)
            ->delete();
    }
};
