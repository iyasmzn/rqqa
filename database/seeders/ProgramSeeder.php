<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $programs = [
        [
            'title' => 'Tahfidz Al-Quran',
            'excerpt' => 'Program unggulan menghafal 30 juz Al-Quran dengan metode talaqqi dan musyafahah langsung kepada pengajar bersanad.',
            'content' => '<p>Program Tahfidz Al-Quran adalah program unggulan Pondok Pesantren Qurrota A\'yun yang telah mencetak ratusan hafidz dan hafidzah berkualitas. Santri diajarkan menghafal Al-Quran dengan metode talaqqi—membaca langsung di hadapan guru—serta musyafahah untuk memastikan kefasihan makharijul huruf.</p><p>Setiap santri mendapatkan pembimbing pribadi (musyrif) yang memantau perkembangan hafalan setiap hari. Program ini ditempuh rata-rata dalam 3–4 tahun.</p><ul><li>Setoran hafalan harian minimal 1 halaman</li><li>Muraja\'ah (pengulangan) hafalan lama setiap minggu</li><li>Ujian tahfidz setiap akhir semester</li><li>Ijazah sanad keilmuan bagi wisudawan</li></ul>',
            'icon' => 'book-open',
            'category' => 'Unggulan',
            'is_published' => true,
            'sort_order' => 1,
        ],
        [
            'title' => 'Madrasah Diniyah',
            'excerpt' => 'Pendidikan agama Islam komprehensif meliputi fiqih, tauhid, akhlak, nahwu-sharaf, dan kajian kitab kuning.',
            'content' => '<p>Madrasah Diniyah adalah pondasi pendidikan Islam di Pesantren Qurrota A\'yun. Santri belajar ilmu-ilmu agama secara sistematis dan terstruktur, mulai dari tingkat Ula, Wustha, hingga Ulya.</p><p>Kurikulum Madrasah Diniyah meliputi:</p><ul><li><strong>Fiqih:</strong> Mabadi Fiqhiyah, Safinatun Naja, Fathul Qarib</li><li><strong>Tauhid:</strong> Aqidatul Awam, Ummu Al-Barahin</li><li><strong>Akhlak:</strong> Ta\'lim Al-Muta\'allim, Akhlaq lil Banin</li><li><strong>Nahwu & Sharaf:</strong> Al-Ajurumiyah, Imrithi, Alfiyah Ibnu Malik</li><li><strong>Tafsir:</strong> Tafsir Al-Jalalain, Tafsir Al-Maraghi</li><li><strong>Hadits:</strong> Bulugh Al-Maram, Riyadhus Shalihin</li></ul>',
            'icon' => 'academic-cap',
            'category' => 'Non-Formal',
            'is_published' => true,
            'sort_order' => 2,
        ],
        [
            'title' => 'Program Terpadu (SMP–SMA)',
            'excerpt' => 'Kombinasi kurikulum nasional (SMP/SMA sederajat) dengan kurikulum pesantren dalam satu atap untuk santri berusia 12–18 tahun.',
            'content' => '<p>Program Terpadu hadir bagi santri yang ingin mendapatkan ijazah formal sekaligus ilmu agama yang mendalam. Santri mengikuti kegiatan belajar formal (SMP/SMA) di pagi hari dan diniyah pesantren di sore hingga malam hari.</p><p>Keunggulan Program Terpadu:</p><ul><li>Ijazah nasional yang diakui pemerintah</li><li>Kurikulum pesantren yang kuat di bidang agama</li><li>Hafalan minimal 5 juz Al-Quran sebagai syarat kelulusan</li><li>Kemampuan baca kitab kuning tingkat dasar</li><li>Pembinaan karakter dan akhlak secara intensif</li></ul>',
            'icon' => 'star',
            'category' => 'Formal',
            'is_published' => true,
            'sort_order' => 3,
        ],
        [
            'title' => 'Bahasa Arab Intensif',
            'excerpt' => 'Kursus bahasa Arab fokus percakapan sehari-hari, qira\'ah, dan kitabah untuk santri dan umum.',
            'content' => '<p>Program Bahasa Arab Intensif dirancang untuk mempercepat kemampuan berkomunikasi dalam bahasa Arab. Program ini cocok bagi santri yang ingin mempersiapkan diri melanjutkan studi ke Timur Tengah maupun bagi masyarakat umum yang ingin memahami Al-Quran lebih baik.</p><p>Materi program meliputi:</p><ul><li>Muhadatsah (percakapan sehari-hari)</li><li>Qira\'ah (membaca teks Arab)</li><li>Kitabah (menulis dalam aksara Arab)</li><li>Qawa\'id (tata bahasa dasar sampai menengah)</li></ul><p>Kelas dibagi menjadi tiga level: Pemula, Menengah, dan Mahir. Setiap level ditempuh dalam 3 bulan.</p>',
            'icon' => 'globe-alt',
            'category' => 'Non-Formal',
            'is_published' => true,
            'sort_order' => 4,
        ],
        [
            'title' => 'Kajian Kitab Kuning',
            'excerpt' => 'Pengkajian mendalam kitab-kitab klasik ulama salaf dengan metode bandongan dan sorogan bersama Kyai.',
            'content' => '<p>Kajian Kitab Kuning adalah ruh dari pendidikan pesantren. Di Qurrota A\'yun, kajian ini dilakukan dengan dua metode utama:</p><p><strong>Metode Bandongan:</strong> Kyai membacakan dan menjelaskan kitab sementara santri menyimak dan mencatat makna di bawah tulisan arab.</p><p><strong>Metode Sorogan:</strong> Santri membaca kitab secara individu di hadapan Kyai untuk dievaluasi kemampuan membaca dan pemahamannya.</p><p>Kitab-kitab yang dikaji antara lain: Ihya Ulumuddin, Al-Umm, Fathul Bari, Al-Mughni, Tafsir Ibnu Katsir, dan berbagai kitab hadits.</p>',
            'icon' => 'heart',
            'category' => 'Unggulan',
            'is_published' => true,
            'sort_order' => 5,
        ],
        [
            'title' => 'Keterampilan & Wirausaha Santri',
            'excerpt' => 'Program vokasional untuk membekali santri dengan keterampilan praktis: pertanian, jahit, komputer, dan wirausaha halal.',
            'content' => '<p>Pesantren tidak hanya mendidik ilmu agama, tetapi juga membekali santri dengan keterampilan hidup yang berguna setelah kembali ke masyarakat. Program ini mencakup:</p><ul><li><strong>Pertanian Organik:</strong> Budidaya sayuran dan buah-buahan tanpa pestisida kimia di lahan pesantren</li><li><strong>Tata Busana:</strong> Menjahit pakaian muslim dan pembuatan perlengkapan shalat</li><li><strong>Teknologi Informasi:</strong> Desain grafis, manajemen media sosial Islami, dan pemrograman dasar</li><li><strong>Wirausaha Halal:</strong> Produksi makanan halal, pemasaran produk, dan manajemen keuangan syariah</li></ul>',
            'icon' => 'wrench-screwdriver',
            'category' => 'Non-Formal',
            'is_published' => true,
            'sort_order' => 6,
        ],
    ];

    public function run(): void
    {
        foreach ($this->programs as $data) {
            Program::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                $data
            );
        }
    }
}
