<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $events = [
        [
            'title' => 'Haflah Akhirussanah 1446 H',
            'excerpt' => 'Perayaan akhir tahun pelajaran pesantren yang menampilkan pentas seni, wisuda, dan penghargaan santri berprestasi.',
            'content' => '<p>Haflah Akhirussanah merupakan agenda tahunan Pondok Pesantren Qurrota A\'yun sebagai bentuk syukur atas selesainya tahun pelajaran. Acara ini menampilkan berbagai pertunjukan seni islami, wisuda santri yang telah menyelesaikan program, serta penghargaan bagi santri-santri berprestasi di bidang akademik maupun hafalan Al-Quran.</p><p>Seluruh wali santri, alumni, dan masyarakat umum diundang untuk hadir dan menyaksikan perkembangan para santri. Acara berlangsung selama dua hari penuh, dimulai dengan khataman Al-Quran dan diakhiri dengan malam gala dinner bersama.</p>',
            'category' => 'Pendidikan',
            'location' => 'Lapangan Utama Pesantren',
            'starts_at' => '2026-06-15 08:00:00',
            'ends_at' => '2026-06-16 21:00:00',
            'is_published' => true,
            'sort_order' => 1,
        ],
        [
            'title' => 'Wisuda Tahfidz Al-Quran Angkatan ke-12',
            'excerpt' => 'Prosesi wisuda 47 santri yang telah berhasil menghafal 30 juz Al-Quran dengan metode talaqqi bersama Kyai.',
            'content' => '<p>Alhamdulillah, pada tahun ini sebanyak 47 santri putra dan putri berhasil menyelesaikan hafalan 30 juz Al-Quran. Prosesi wisuda tahfidz akan dilaksanakan dengan khidmat, dihadiri oleh para wali santri, tokoh masyarakat, dan tamu undangan.</p><p>Para wisudawan akan mendapatkan ijazah sanad keilmuan yang tersambung kepada Rasulullah SAW, sebuah kehormatan yang tak ternilai bagi setiap penghafal Al-Quran.</p>',
            'category' => 'Keagamaan',
            'location' => 'Masjid Jami\' Al-Falah Pesantren',
            'starts_at' => '2026-07-03 09:00:00',
            'ends_at' => '2026-07-03 13:00:00',
            'is_published' => true,
            'sort_order' => 2,
        ],
        [
            'title' => 'Pengajian Umum: Kitab Nashaihul Ibad',
            'excerpt' => 'Pengajian kitab kuning terbuka untuk umum bersama KH. Ahmad Zaini Dahlan setiap malam Jumat.',
            'content' => '<p>Pengajian umum rutin malam Jumat hadir kembali dengan mengkaji Kitab Nashaihul Ibad karya Syaikh Muhammad Nawawi Al-Bantani. Kitab ini berisi nasihat-nasihat berharga bagi para hamba Allah dalam menjalani kehidupan sehari-hari.</p><p>Terbuka untuk seluruh masyarakat umum, baik putra maupun putri. Disediakan konsumsi gratis dan kitab cetak bagi peserta yang hadir langsung.</p>',
            'category' => 'Keagamaan',
            'location' => 'Aula Utama Pesantren',
            'starts_at' => '2026-06-06 20:00:00',
            'ends_at' => '2026-06-06 22:00:00',
            'is_published' => true,
            'sort_order' => 3,
        ],
        [
            'title' => 'Seminar Nasional: Peran Pesantren di Era Digital',
            'excerpt' => 'Seminar nasional membahas strategi pesantren dalam menghadapi tantangan era digital dan mencetak generasi islami yang berkarakter.',
            'content' => '<p>Era digital membawa peluang sekaligus tantangan bagi lembaga pendidikan Islam. Seminar ini menghadirkan pembicara dari berbagai kalangan — akademisi, praktisi teknologi, dan ulama — untuk berdiskusi mengenai strategi terbaik pesantren dalam memanfaatkan teknologi tanpa kehilangan nilai-nilai tradisionalnya.</p><p>Peserta akan mendapatkan sertifikat nasional dan materi seminar dalam bentuk e-book.</p>',
            'category' => 'Pendidikan',
            'location' => 'Gedung Serbaguna Pesantren',
            'starts_at' => '2026-08-20 08:00:00',
            'ends_at' => '2026-08-20 16:00:00',
            'is_published' => true,
            'sort_order' => 4,
        ],
        [
            'title' => 'Lomba Pidato Bahasa Arab Tingkat Jawa Timur',
            'excerpt' => 'Kompetisi pidato bahasa Arab antar santri se-Jawa Timur memperebutkan piala bergilir Gubernur Jawa Timur.',
            'content' => '<p>Pesantren Qurrota A\'yun kembali menjadi tuan rumah lomba pidato bahasa Arab tingkat Jawa Timur. Kompetisi ini diikuti oleh santri dari lebih dari 50 pesantren di seluruh Jawa Timur, dibagi menjadi kategori Tsanawiyah dan Aliyah.</p><p>Hadiah utama berupa piala bergilir, beasiswa pendidikan, dan kesempatan mengikuti program bahasa Arab di Timur Tengah.</p>',
            'category' => 'Pendidikan',
            'location' => 'Aula Utama Pesantren',
            'starts_at' => '2026-09-14 08:00:00',
            'ends_at' => '2026-09-15 17:00:00',
            'is_published' => true,
            'sort_order' => 5,
        ],
        [
            'title' => 'Pesantren Kilat Ramadhan 1447 H',
            'excerpt' => 'Program intensif 10 hari selama Ramadhan: tadabbur Al-Quran, kajian fiqih puasa, dan i\'tikaf bersama.',
            'content' => '<p>Program Pesantren Kilat Ramadhan hadir untuk para pelajar dan mahasiswa yang ingin memaksimalkan ibadah selama bulan suci. Kegiatan berlangsung selama 10 hari dengan agenda padat: sahur bersama, tadarus, kajian Tafsir, kajian Fiqih Shaum, shalat tarawih berjamaah, dan i\'tikaf di 10 malam terakhir.</p><p>Terbuka untuk peserta usia 13–25 tahun. Kuota terbatas 150 peserta.</p>',
            'category' => 'Keagamaan',
            'location' => 'Kompleks Pesantren Qurrota A\'yun',
            'starts_at' => '2026-03-15 17:00:00',
            'ends_at' => '2026-03-25 12:00:00',
            'is_published' => true,
            'sort_order' => 6,
        ],
        [
            'title' => 'Maulid Nabi Muhammad SAW 1447 H',
            'excerpt' => 'Peringatan maulid nabi bersama ribuan jamaah, pembacaan maulid Al-Barzanji, dan ceramah dari ulama tamu.',
            'content' => '<p>Pondok Pesantren Qurrota A\'yun mengadakan peringatan Maulid Nabi Muhammad SAW 1447 H yang terbuka untuk seluruh masyarakat. Rangkaian acara meliputi pembacaan Maulid Al-Barzanji, shalawat bersama, ceramah dari KH. Abdullah Faqih, dan doa bersama.</p><p>Acara ini juga dimeriahkan oleh kelompok hadrah dari berbagai pesantren di Jawa Timur.</p>',
            'category' => 'Keagamaan',
            'location' => 'Lapangan Utama Pesantren',
            'starts_at' => '2026-09-05 19:00:00',
            'ends_at' => '2026-09-05 22:30:00',
            'is_published' => true,
            'sort_order' => 7,
        ],
        [
            'title' => 'Festival Seni Islami Nusantara',
            'excerpt' => 'Festival seni budaya islami menampilkan kaligrafi, kasidah, hadroh, tari saman, dan pameran karya santri.',
            'content' => '<p>Festival Seni Islami Nusantara merupakan ajang ekspresi kreativitas para santri dalam bingkai nilai-nilai Islam. Tahun ini menampilkan kompetisi kaligrafi Al-Quran, pentas hadroh, kasidah rebana, tari saman, dan pameran karya seni santri.</p><p>Festival ini juga diikuti oleh komunitas seni islami dari luar pesantren sebagai bentuk kolaborasi budaya yang memperkuat ukhuwah islamiyah.</p>',
            'category' => 'Budaya',
            'location' => 'Aula Utama & Halaman Pesantren',
            'starts_at' => '2026-10-10 09:00:00',
            'ends_at' => '2026-10-11 21:00:00',
            'is_published' => true,
            'sort_order' => 8,
        ],
        [
            'title' => 'Reuni Alumni Angkatan 2010–2015',
            'excerpt' => 'Pertemuan tahunan alumni pesantren untuk mempererat silaturahmi, berbagi pengalaman, dan berkontribusi bagi almamater.',
            'content' => '<p>Reuni alumni angkatan 2010–2015 hadir kembali setelah dua tahun vakum. Acara ini menjadi momentum untuk mempererat tali silaturahmi antar alumni, berbagi pengalaman dalam berkarir dan berkhidmat kepada masyarakat, serta mendiskusikan kontribusi alumni bagi perkembangan pesantren.</p><p>Tersedia program wisata religi ke makam para pendiri pesantren dan malam keakraban bersama keluarga besar Qurrota A\'yun.</p>',
            'category' => 'Sosial',
            'location' => 'Gedung Serbaguna Pesantren',
            'starts_at' => '2026-12-26 09:00:00',
            'ends_at' => '2026-12-27 15:00:00',
            'is_published' => true,
            'sort_order' => 9,
        ],
        [
            'title' => 'Khataman Al-Quran Bi Al-Ghaib Bersama',
            'excerpt' => 'Khataman 30 juz Al-Quran secara bersama-sama oleh seluruh santri tahfidz putra dan putri.',
            'content' => '<p>Setiap bulan, seluruh santri program tahfidz mengikuti khataman Al-Quran secara bersama. Setiap santri mendapat jatah beberapa juz untuk dibaca secara tartil, sehingga dalam satu majelis dapat menyelesaikan 30 juz sekaligus.</p><p>Acara ini dipimpin langsung oleh KH. Luqmanul Hakim dan diakhiri dengan doa khatmil Quran serta jamuan makan bersama.</p>',
            'category' => 'Keagamaan',
            'location' => 'Masjid Jami\' Al-Falah Pesantren',
            'starts_at' => '2026-06-28 07:00:00',
            'ends_at' => '2026-06-28 12:00:00',
            'is_published' => true,
            'sort_order' => 10,
        ],
    ];

    public function run(): void
    {
        foreach ($this->events as $data) {
            Event::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                $data
            );
        }
    }
}
