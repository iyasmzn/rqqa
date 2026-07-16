<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        if (Post::exists()) {
            return;
        }

        $posts = [
            [
                'title' => 'Selamat Datang di Website Resmi Pondok Pesantren',
                'slug' => 'selamat-datang-di-website-resmi-pondok-pesantren',
                'excerpt' => 'Website resmi Pondok Pesantren kini hadir untuk memudahkan akses informasi seputar program pendidikan, kegiatan, penerimaan santri baru, dan kisah inspiratif dari pesantren.',
                'content' => '<p>Bismillahirrahmanirrahim. Selamat datang di website resmi Pondok Pesantren! Kami dengan bangga mempersembahkan portal informasi digital yang dirancang untuk memudahkan seluruh warga pesantren, wali santri, dan masyarakat luas dalam mengakses berbagai informasi penting.</p><h2>Fitur Unggulan Website</h2><p>Melalui website ini, Anda dapat menemukan berbagai informasi meliputi profil dan visi-misi pesantren, program pendidikan tahfidz dan diniyah, kegiatan dan agenda pesantren, kisah inspiratif santri, toko buku pesantren, serta formulir pendaftaran santri baru secara online.</p><p>Kami berkomitmen untuk terus memperbarui konten website ini agar selalu relevan dan informatif. Jangan ragu untuk menghubungi kami jika ada pertanyaan atau masukan. Jazakumullah khairan.</p>',
                'category' => 'Berita',
                'author' => 'Tim Humas',
                'author_initials' => 'TH',
                'read_time' => 2,
                'is_published' => true,
                'published_at' => now()->subDays(30),
            ],
            [
                'title' => 'Penerimaan Santri Baru 2026/2027 Resmi Dibuka — Syarat dan Alur Pendaftaran',
                'slug' => 'penerimaan-santri-baru-2026-2027-resmi-dibuka',
                'excerpt' => 'Pondok Pesantren membuka penerimaan santri baru tahun ajaran 2026/2027. Tersedia program Tahfidz, Diniyah, dan Terpadu. Simak syarat dan alur pendaftarannya.',
                'content' => "<p>Alhamdulillah, Pondok Pesantren dengan bangga mengumumkan pembukaan resmi Penerimaan Santri Baru (PSB) untuk Tahun Ajaran 2026/2027. Pendaftaran dibuka mulai hari ini hingga 30 Juni 2026.</p><h2>Program yang Tersedia</h2><ul><li><strong>Program Tahfidz</strong> — Fokus hafalan Al-Qur'an 30 juz dengan target minimal 1 juz per bulan, dibimbing langsung oleh hafidz berpengalaman.</li><li><strong>Program Diniyah</strong> — Kajian kitab kuning klasik meliputi fikih, tauhid, nahwu-sharaf, dan akhlak tasawuf.</li><li><strong>Program Terpadu</strong> — Kombinasi tahfidz dan pendidikan formal (SMP/SMA) untuk santri yang ingin meraih dua keunggulan sekaligus.</li></ul><h2>Persyaratan Umum</h2><p>Calon santri diwajibkan menyiapkan: fotokopi Kartu Keluarga (3 lembar), fotokopi akta kelahiran, fotokopi ijazah/SKHUN, pas foto terbaru 3×4 (6 lembar), dan mengisi formulir pendaftaran online.</p><p>Pendaftaran dapat dilakukan secara online melalui halaman PPDB di website ini atau langsung datang ke kantor pesantren. Untuk informasi lebih lanjut, hubungi panitia PSB via WhatsApp.</p>",
                'category' => 'Pengumuman',
                'author' => 'Panitia PSB',
                'author_initials' => 'PS',
                'read_time' => 4,
                'is_published' => true,
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Santri Berprestasi Raih Juara 1 Musabaqah Hifdzil Qur\'an Tingkat Nasional',
                'slug' => 'santri-raih-juara-1-musabaqah-hifdzil-quran-tingkat-nasional',
                'excerpt' => "Membanggakan! Salah satu santri kami berhasil meraih juara pertama pada Musabaqah Hifdzil Qur'an (MHQ) 30 juz tingkat nasional yang diselenggarakan di Jakarta.",
                'content' => "<p>Subhanallah. Kebanggaan dan rasa syukur yang luar biasa menyelimuti seluruh keluarga besar Pondok Pesantren. Santri terbaik kami, Muhammad Farhan (19), berhasil meraih Juara Pertama pada Musabaqah Hifdzil Qur'an (MHQ) 30 Juz Tingkat Nasional yang diselenggarakan di Jakarta pekan lalu.</p><h2>Perjalanan Panjang Menuju Juara</h2><p>Farhan yang telah mukim di pesantren sejak usia 13 tahun ini menyelesaikan hafalan 30 juz dalam waktu 3 tahun dengan kualitas tajwid dan makhraj yang sangat baik. Ia kemudian mengabdikan diri setahun penuh untuk memurojaah dan mempersiapkan diri menghadapi kompetisi bergengsi ini.</p><p>\"Ini semua adalah karunia Allah SWT dan buah dari doa seluruh asatidz, wali santri, dan teman-teman yang selalu mendukung,\" ungkap Farhan dengan penuh keharuan usai dinobatkan sebagai juara.</p><h2>Ucapan dari Pengasuh</h2><p>KH. Abdullah Mubarok, Lc., M.A. selaku Kepala Yayasan menyampaikan rasa bangga dan doa terbaik untuk Farhan. Beliau berharap prestasi ini menjadi inspirasi bagi seluruh santri untuk terus semangat dalam menghafal dan menjaga Al-Qur'an.</p>",
                'category' => 'Prestasi',
                'author' => 'Ust. Ahmad Ridwan',
                'author_initials' => 'AR',
                'read_time' => 4,
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'Kegiatan Haul Akbar dan Khatmil Qur\'an Ke-32 Berlangsung Khidmat',
                'slug' => 'haul-akbar-dan-khatmil-quran-ke-32',
                'excerpt' => "Haul Akbar dan Khatmil Qur'an ke-32 Pondok Pesantren berlangsung khidmat dan penuh keberkahan, dihadiri ribuan jamaah dari berbagai daerah.",
                'content' => "<p>Alhamdulillah, Haul Akbar dan Khatmil Qur'an ke-32 Pondok Pesantren telah berlangsung dengan khidmat dan penuh keberkahan pada Sabtu, 20 Mei 2026 lalu. Ribuan jamaah dari berbagai daerah hadir untuk mengikuti rangkaian acara yang berlangsung sejak Jum'at malam.</p><h2>Rangkaian Acara</h2><p>Kegiatan diawali dengan khataman Al-Qur'an bil-ghaib oleh 50 santri hafidz pada Jum'at malam, dilanjutkan dengan istighosah akbar, tahlil, dan doa bersama. Puncak acara pada Sabtu pagi diisi dengan tausiyah oleh ulama nasional dan prosesi wisuda santri angkatan ke-28.</p><p>Sebanyak 45 santri diwisuda dalam kesempatan tersebut, 12 di antaranya berhasil menyelesaikan hafalan 30 juz. Momen ini menjadi puncak kebanggaan dan kebahagiaan bagi seluruh keluarga besar pesantren.</p><h2>Pesan dari Pengasuh</h2><p>Dalam tausiyahnya, KH. Abdullah Mubarok berpesan agar para alumni selalu menjaga hafalan Al-Qur'an dan mengamalkan ilmu yang telah didapatkan di pesantren. \"Al-Qur'an adalah cahaya. Jaga ia, dan ia akan menjaga kalian,\" pesan beliau.</p>",
                'category' => 'Kegiatan',
                'author' => 'Tim Humas',
                'author_initials' => 'TH',
                'read_time' => 3,
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Program Beasiswa Santri Berprestasi — Daftar Sebelum Batas Waktu',
                'slug' => 'program-beasiswa-santri-berprestasi',
                'excerpt' => 'Pondok Pesantren membuka program beasiswa penuh bagi calon santri berprestasi dari keluarga kurang mampu. Beasiswa mencakup biaya pendidikan, makan, dan tempat tinggal.',
                'content' => "<p>Dalam rangka mewujudkan visi pesantren sebagai lembaga pendidikan Islam yang inklusif, Pondok Pesantren kembali membuka Program Beasiswa Santri Berprestasi (PBSB) untuk tahun ajaran 2026/2027.</p><h2>Cakupan Beasiswa</h2><p>Beasiswa yang diberikan bersifat penuh (full scholarship) dan mencakup:</p><ul><li>Biaya pendidikan (SPP) selama masa studi</li><li>Biaya makan tiga kali sehari</li><li>Akomodasi (kamar) di pesantren</li><li>Kitab dan perlengkapan belajar</li><li>Tunjangan seragam pesantren</li></ul><h2>Persyaratan Pendaftar</h2><p>Calon penerima beasiswa harus memenuhi kriteria berikut:</p><ul><li>Hafal minimal 5 juz Al-Qur'an (untuk program tahfidz)</li><li>Berasal dari keluarga kurang mampu (dilampirkan surat keterangan)</li><li>Memiliki akhlak dan perilaku yang baik (rekomendasi ustadz/kyai)</li><li>Bersedia tinggal mukim di pesantren</li></ul><p>Pendaftaran dibuka hingga 15 Juni 2026. Hubungi bagian administrasi pesantren atau melalui WhatsApp resmi untuk informasi lebih lanjut.</p>",
                'category' => 'Pengumuman',
                'author' => 'Ust. Hasan Basri',
                'author_initials' => 'HB',
                'read_time' => 4,
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Metode Talaqi — Cara Efektif Hafalan Al-Qur\'an ala Pesantren',
                'slug' => 'metode-talaqi-cara-efektif-hafalan-al-quran',
                'excerpt' => "Mengenal metode talaqi yang diterapkan di pesantren kami — cara menghafal Al-Qur'an yang efektif, teruji, dan sudah melahirkan ratusan hafidz-hafidzah.",
                'content' => "<p>Salah satu keunggulan Pondok Pesantren adalah penerapan metode talaqi dalam proses hafalan Al-Qur'an. Metode ini telah terbukti efektif dan telah melahirkan lebih dari 200 hafidz-hafidzah Al-Qur'an sejak pesantren berdiri.</p><h2>Apa itu Metode Talaqi?</h2><p>Talaqi berasal dari kata 'laqa' yang berarti bertemu atau berjumpa. Dalam konteks hafalan Al-Qur'an, talaqi adalah metode di mana santri menyetorkan hafalan secara langsung (bertatap muka) kepada guru hafidz. Guru akan mendengarkan, mengoreksi tajwid dan makhraj, serta memberikan bimbingan personal kepada setiap santri.</p><h2>Sistem Hafalan di Pesantren Kami</h2><p>Setiap santri memiliki jadwal setoran harian minimal ½ halaman (bagi pemula) hingga 1–2 halaman (bagi yang sudah lancar). Selain setoran hafalan baru, santri juga wajib muroja'ah (mengulang) hafalan lama setiap hari agar tidak mudah lupa.</p><p>Dengan sistem ini, santri yang tekun dapat menyelesaikan hafalan 30 juz dalam waktu 3–5 tahun dengan kualitas yang terjaga. Alhamdulillah, metode ini telah teruji dan terus menghasilkan generasi penghafal Al-Qur'an yang berkualitas.</p>",
                'category' => 'Akademik',
                'author' => 'Ust. Mahmud Al-Hafidz',
                'author_initials' => 'MA',
                'read_time' => 5,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
        ];

        foreach ($posts as $data) {
            Post::create($data);
        }
    }
}
