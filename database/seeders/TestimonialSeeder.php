<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        if (Testimonial::exists()) {
            return;
        }

        $testimonials = [
            [
                'name' => 'Rahmat Hidayat',
                'class_year' => '2018',
                'graduation_year' => '2021',
                'message' => 'Pondok Pesantren benar-benar membentuk karakter saya. Guru-guru yang berdedikasi dan lingkungan yang kondusif membuat saya berkembang bukan hanya secara akademik, tapi juga sebagai pribadi yang lebih baik.',
                'photo' => null,
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sari Anggraini',
                'class_year' => '2019',
                'graduation_year' => '2022',
                'message' => 'Kenangan bersekolah di sini tidak akan pernah terlupakan. Saya belajar bukan hanya ilmu pengetahuan, tapi juga nilai-nilai kehidupan yang hingga hari ini masih saya terapkan dalam karier dan kehidupan sehari-hari.',
                'photo' => null,
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Fajar Nugroho',
                'class_year' => '2017',
                'graduation_year' => '2020',
                'message' => 'Di sini saya menemukan bakat dan potensi yang tidak pernah saya sadari sebelumnya. Ekstrakurikuler yang beragam dan pembimbing yang supportif adalah kunci berkembangnya kemampuan saya di bidang robotika dan teknologi.',
                'photo' => null,
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Indah Permatasari',
                'class_year' => '2020',
                'graduation_year' => '2023',
                'message' => 'Saya sangat bersyukur bisa bersekolah di Pondok Pesantren. Beasiswa yang saya dapatkan memungkinkan saya untuk fokus belajar tanpa khawatir biaya. Sekarang saya sudah diterima di universitas negeri impian saya.',
                'photo' => null,
                'is_published' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Dimas Prasetyo',
                'class_year' => '2016',
                'graduation_year' => '2019',
                'message' => 'Guru-guru di sini benar-benar pahlawan tanpa tanda jasa. Mereka tidak hanya mengajar di kelas, tapi juga membimbing kami di luar jam pelajaran dengan penuh kesabaran dan ketulusan.',
                'photo' => null,
                'is_published' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Rini Setiawati',
                'class_year' => '2021',
                'graduation_year' => '2024',
                'message' => 'Tiga tahun di Pondok Pesantren adalah masa paling berkesan dalam hidup saya. Teman-teman yang solid, guru yang inspiratif, dan kegiatan yang beragam membuat setiap hari terasa bermakna.',
                'photo' => null,
                'is_published' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Andi Maulana',
                'class_year' => '2015',
                'graduation_year' => '2018',
                'message' => 'Saya meraih banyak prestasi berkat dukungan penuh dari sekolah. Olimpiade, lomba debat, hingga pertukaran pelajar — semua pengalaman itu tidak mungkin saya dapatkan tanpa fasilitas dan bimbingan dari Pondok Pesantren.',
                'photo' => null,
                'is_published' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Fitri Handayani',
                'class_year' => '2022',
                'graduation_year' => '2025',
                'message' => 'Pesan saya untuk adik-adik yang ingin mendaftar: jangan ragu! Pondok Pesantren adalah pilihan yang tepat. Di sini kalian akan dibimbing menjadi pribadi yang berilmu, berkarakter, dan siap menghadapi dunia.',
                'photo' => null,
                'is_published' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::create($data);
        }
    }
}
