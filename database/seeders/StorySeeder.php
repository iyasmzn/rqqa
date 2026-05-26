<?php

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StorySeeder extends Seeder
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $stories = [
        [
            'title' => 'Dari Kampung Kecil Menuju Hafidz Al-Quran',
            'author_name' => 'Muhammad Fauzan Al-Hafidz',
            'author_class' => 'Alumni Kelas 3 Aliyah',
            'author_year' => '2023',
            'excerpt' => 'Saya datang dari desa kecil di Madura dengan modal nekat dan doa orang tua. Empat tahun kemudian, saya pulang sebagai hafidz 30 juz.',
            'content' => '<p>Nama saya Fauzan, asli dari Pamekasan, Madura. Ketika pertama kali menginjakkan kaki di Pondok Pesantren Qurrota A\'yun, saya masih berusia 13 tahun. Tas ransel lusuh, uang saku pas-pasan, dan hati yang campur aduk antara semangat dan takut.</p><p>Awal-awal sangat berat. Bangun jam 3 pagi, rutinitas yang ketat, jauh dari keluarga. Sering saya menangis di pojok kamar, ingin pulang. Tapi wajah Abi dan Umi yang melepas saya dengan linangan air mata selalu menguatkan langkah.</p><p>Yang membuat saya bertahan adalah suasana pesantren yang penuh barakah. Kyai dan ustadz bukan hanya mengajar, tetapi benar-benar membimbing. Teman-teman seperjalanan yang saling menyemangati. Dan Al-Quran, yang setiap hari saya hafal satu per satu ayatnya.</p><p>Empat tahun berlalu. Pada Wisuda Tahfidz ke-11, nama saya dipanggil. Saya maju, menangis sujud syukur. Tiga puluh juz selesai. Bukan karena saya pintar—tapi karena Allah memudahkan dan keluarga serta pesantren ini tidak pernah berhenti mendoakan.</p>',
            'is_published' => true,
            'published_at' => '2023-08-15 10:00:00',
            'sort_order' => 1,
        ],
        [
            'title' => 'Belajar Kitab Kuning di Usia 30 Tahun',
            'author_name' => 'Ustadz Hasan Basri',
            'author_class' => 'Santri Dewasa Program Khusus',
            'author_year' => '2024',
            'excerpt' => 'Saya guru SD yang memutuskan mondok di usia 30 untuk memperdalam ilmu agama. Tidak ada kata terlambat untuk belajar.',
            'content' => '<p>Orang bilang saya gila ketika memutuskan mondok di usia 30 tahun. Saya sudah punya keluarga, karir yang stabil sebagai guru SD, dan rumah sendiri. Tapi ada kekosongan yang mengganjal—saya mengajar Pendidikan Agama Islam, namun merasa ilmu saya tidak cukup mendalam.</p><p>Dengan izin istri yang luar biasa, saya mendaftar ke program khusus dewasa di Qurrota A\'yun. Satu tahun penuh saya tinggal di pesantren, meninggalkan keluarga di Surabaya setiap Senin dan pulang setiap Jumat sore.</p><p>Awalnya minder. Teman-teman saya di kelas kebanyakan berusia 17–20 tahun. Mereka lebih cepat menghafal, lebih luwes berbahasa Arab. Tapi Pak Kyai selalu berkata: "Yang tua punya kematangan berpikir yang tak dimiliki yang muda."</p><p>Setahun berlalu. Saya pulang dengan bekal yang sangat berharga: kemampuan membaca kitab kuning, pemahaman nahwu yang cukup, dan yang terpenting—cara pandang yang lebih hikmah dalam mendidik anak-anak murid saya.</p>',
            'is_published' => true,
            'published_at' => '2024-02-20 09:00:00',
            'sort_order' => 2,
        ],
        [
            'title' => 'Juara Pidato Arab Nasional: Buah dari Kesabaran',
            'author_name' => 'Siti Maryam Az-Zahra',
            'author_class' => 'Kelas 2 Aliyah',
            'author_year' => '2025',
            'excerpt' => 'Dua kali gagal, berkali-kali menangis latihan tengah malam. Akhirnya juara satu pidato bahasa Arab tingkat nasional.',
            'content' => '<p>Pertama kali ikut lomba pidato bahasa Arab, saya gugur di babak penyisihan. Kedua kalinya, saya kalah di semifinal. Tangisan dan frustrasi sempat membuat saya ingin menyerah.</p><p>Tapi Ustadzah Khoiriyyah tidak mengizinkan saya menyerah. Setiap malam setelah shalat Isya, beliau menemani saya latihan di kelas. Mengkoreksi makhroj, memperhalus intonasi, memperbaiki gestur. Kadang kami baru selesai jam 11 malam.</p><p>"Bahasa Arab itu bukan sekadar kata-kata," kata Ustadzah. "Ia adalah ruh yang harus kamu rasakan dalam hati sebelum disampaikan ke telinga orang lain."</p><p>Enam bulan intensif. Di ajang Musabaqah Pidato Bahasa Arab tingkat nasional di Jakarta, nama saya dipanggil sebagai juara pertama putri. Saya tidak percaya. Saya menelepon Umi, menangis bersama di ujung telepon.</p><p>Pelajaran terbesar saya: kegagalan bukan akhir, ia adalah bahan bakar. Dan seorang guru yang percaya kepada kita lebih dari kita percaya pada diri sendiri—itu segalanya.</p>',
            'is_published' => true,
            'published_at' => '2025-03-10 08:00:00',
            'sort_order' => 3,
        ],
        [
            'title' => 'Dari Santri Nakal Menjadi Pengurus OSIS',
            'author_name' => 'Ahmad Ridho Saputra',
            'author_class' => 'Kelas 3 Tsanawiyah',
            'author_year' => '2025',
            'excerpt' => 'Saya masuk pesantren karena "dibuang" orang tua yang sudah putus asa. Tiga tahun kemudian, saya memimpin organisasi santri.',
            'content' => '<p>Jujur, saya masuk pesantren bukan karena keinginan sendiri. Orang tua sudah frustrasi—nilai di sekolah hancur, sering bolos, bergaul dengan teman yang tidak baik. Pesantren adalah "hukuman" terakhir mereka.</p><p>Bulan pertama saya sempat kabur dua kali. Tapi setiap kali saya kabur, Pak Kyai tidak pernah marah. Beliau hanya bertanya dengan lembut: "Apa yang membuatmu tidak betah, Nak?" Pertanyaan itu membekukan saya lebih dari amarah.</p><p>Perlahan saya mulai membuka diri. Seorang musyrif (pembimbing) senior yang bernama Kak Irfan mengajak saya bergabung di tim redaksi majalah dinding pesantren. Dari situ, saya menemukan bahwa saya suka menulis dan berdiskusi.</p><p>Tahun ketiga, saya terpilih menjadi Ketua OSIS. Orang tua yang datang saat pelantikan tidak bisa menyembunyikan air mata mereka. Saya juga tidak bisa.</p>',
            'is_published' => true,
            'published_at' => '2025-07-22 11:00:00',
            'sort_order' => 4,
        ],
        [
            'title' => 'Meraih Beasiswa ke Al-Azhar Berkat Pesantren',
            'author_name' => 'Abdullah Karim Nasution',
            'author_class' => 'Alumni Kelas 3 Aliyah',
            'author_year' => '2024',
            'excerpt' => 'Impian kuliah di Al-Azhar Mesir terwujud berkat fondasi bahasa Arab dan ilmu agama yang kuat dari pesantren.',
            'content' => '<p>Sejak kelas 1 Tsanawiyah, saya sudah bermimpi kuliah di Universitas Al-Azhar Kairo, Mesir—pusat keilmuan Islam tertua di dunia. Impian itu sering saya tertawakan oleh diri sendiri: "Anak desa dari Jawa Timur bisa apa?"</p><p>Tapi pesantren mengajarkan saya bahwa mimpi itu diwujudkan dengan ikhtiar dan tawakkal, bukan dengan berdiam diri.</p><p>Selama enam tahun di Qurrota A\'yun, saya belajar bahasa Arab dengan sungguh-sungguh, menghafal Al-Quran 15 juz, dan menguasai puluhan kitab kuning. Ustadz saya, Ustadz Mahfudz, mempersiapkan saya untuk ujian seleksi beasiswa Kemenag sejak kelas 2 Aliyah.</p><p>Alhamdulillah. Pada tahun 2024, saya dinyatakan lulus seleksi beasiswa penuh Kementerian Agama RI untuk melanjutkan studi S1 di Fakultas Syariah Universitas Al-Azhar. Impian itu nyata.</p>',
            'is_published' => true,
            'published_at' => '2024-11-05 14:00:00',
            'sort_order' => 5,
        ],
        [
            'title' => 'Kemandirian yang Saya Pelajari dari Dapur Pesantren',
            'author_name' => 'Fatimah Nur Halimah',
            'author_class' => 'Alumni Kelas 3 Aliyah',
            'author_year' => '2022',
            'excerpt' => 'Di rumah semua dilayani asisten. Di pesantren saya harus cuci piring sendiri. Ternyata itulah pelajaran paling berharga.',
            'content' => '<p>Saya anak orang berada. Di rumah ada asisten yang mengurus semua kebutuhan. Ketika pertama kali tiba di pesantren dan tahu harus antri cuci piring sendiri, jujur saya menangis dan ingin pulang.</p><p>Tapi itulah titik balik terbesar dalam hidup saya.</p><p>Di pesantren, semua santri sama. Tidak ada yang lebih istimewa karena ayahnya pengusaha atau ibunya pejabat. Semua antri di kran yang sama, makan di tempat yang sama, tidur di kamar yang sama. Kesetaraan itu mengajarkan saya tentang manusia yang sesungguhnya.</p><p>Enam tahun di pesantren membentuk saya menjadi perempuan yang mandiri, tangguh, dan—yang paling penting—memiliki kepekaan sosial yang tidak bisa dibeli dengan apapun. Sekarang saya mengelola rumah tangga dan bisnis sendiri dengan penuh percaya diri.</p><p>Terimakasih, Pesantren Qurrota A\'yun. Kamu mengajarkan saya bahwa hidup yang bermartabat bukan tentang dilayani, melainkan tentang melayani.</p>',
            'is_published' => true,
            'published_at' => '2022-09-18 09:30:00',
            'sort_order' => 6,
        ],
        [
            'title' => 'Tahfidz dan Kuliah Kedokteran: Mungkin!',
            'author_name' => 'Zainab Qurrota Aini',
            'author_class' => 'Alumni Kelas 3 Aliyah',
            'author_year' => '2023',
            'excerpt' => 'Hafidz 30 juz sekaligus lulus SNBT masuk FK Universitas Airlangga. Dua impian yang saya wujudkan bersamaan.',
            'content' => '<p>Banyak orang bertanya: "Bagaimana bisa hafidz Al-Quran sekaligus belajar untuk masuk Fakultas Kedokteran?" Pertanyaan yang wajar, karena keduanya membutuhkan waktu, energi, dan konsentrasi yang besar.</p><p>Jawabannya: manajemen waktu yang ketat, dan berkah dari Al-Quran itu sendiri.</p><p>Di pesantren, saya belajar bahwa disiplin waktu adalah seni. Setelah Shubuh untuk hafalan baru. Pagi untuk pelajaran formal. Siang istirahat sejenak. Sore muraja\'ah sambil jalan di halaman. Malam untuk belajar pelajaran umum.</p><p>Para ulama berkata: "Barangsiapa menjaga Al-Quran, Allah akan menjaga urusannya." Saya membuktikan itu bukan hanya sebuah janji—ia adalah kenyataan. Alhamdulillah, saya diterima di FK Universitas Airlangga dengan hafalan 30 juz yang sudah rampung.</p>',
            'is_published' => true,
            'published_at' => '2023-10-01 10:00:00',
            'sort_order' => 7,
        ],
        [
            'title' => 'Persahabatan yang Lahir di Pesantren, Bertahan Seumur Hidup',
            'author_name' => 'Umar Faruq Ibrahim',
            'author_class' => 'Alumni Kelas 3 Aliyah',
            'author_year' => '2021',
            'excerpt' => 'Teman sekamar yang awalnya menjengkelkan kini menjadi saudara terbaik. Persahabatan di pesantren punya kualitas yang berbeda.',
            'content' => '<p>Awal mondok, saya sekamar dengan 11 orang lain. Salah satunya, Fuad, sangat menjengkelkan. Suka begadang, sering nyanyi keras-keras, dan selalu meminjam barang tanpa bilang.</p><p>Tapi ada sesuatu yang ajaib dari tinggal bersama dalam satu kamar selama bertahun-tahun. Kamu melihat sisi terdalam seseorang—ketika dia menangis diam-diam karena rindu rumah, ketika dia bangun lebih awal untuk shalat tahajud, ketika dia berbagi lauk sederhana dengan setulus-tulusnya.</p><p>Fuad, teman yang awalnya menjengkelkan itu, kini adalah saudara terbaik saya. Dia yang paling pertama ada ketika Abi saya sakit. Dia yang menemani saya melamar kerja pertama kali. Dia yang jadi saksi pernikahan saya.</p><p>Persahabatan di pesantren lahir dari ujian bersama, tumbuh dari ketulusan, dan bertahan karena ada ikatan iman. Itulah yang membedakannya dari persahabatan di tempat lain.</p>',
            'is_published' => true,
            'published_at' => '2021-04-12 08:00:00',
            'sort_order' => 8,
        ],
    ];

    public function run(): void
    {
        foreach ($this->stories as $data) {
            Story::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                $data
            );
        }
    }
}
