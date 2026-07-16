<?php

namespace Database\Seeders;

use App\Models\Download;
use Illuminate\Database\Seeder;

class DownloadSeeder extends Seeder
{
    public function run(): void
    {
        if (Download::exists()) {
            return;
        }

        $downloads = [
            [
                'title' => 'Formulir Pendaftaran SPMB 2026/2027',
                'description' => 'Formulir pendaftaran resmi untuk Seleksi Penerimaan Murid Baru tahun ajaran 2026/2027.',
                'category' => 'Formulir',
                'file_path' => 'downloads/formulir-spmb-2026-2027.pdf',
                'original_filename' => 'Formulir-SPMB-2026-2027.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 524288,
                'download_count' => 0,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Kalender Akademik Tahun Ajaran 2025/2026',
                'description' => 'Jadwal lengkap kegiatan akademik, ujian, libur nasional, dan hari efektif belajar sepanjang tahun ajaran 2025/2026.',
                'category' => 'Kalender',
                'file_path' => 'downloads/kalender-akademik-2025-2026.pdf',
                'original_filename' => 'Kalender-Akademik-2025-2026.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 786432,
                'download_count' => 0,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Surat Edaran Tata Tertib Siswa',
                'description' => 'Peraturan dan tata tertib yang wajib dipatuhi oleh seluruh santri Pondok Pesantren.',
                'category' => 'Surat Edaran',
                'file_path' => 'downloads/tata-tertib-siswa.pdf',
                'original_filename' => 'Tata-Tertib-Siswa.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 409600,
                'download_count' => 0,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Jadwal Ujian Akhir Semester Genap 2024/2025',
                'description' => 'Jadwal lengkap Ujian Akhir Semester (UAS) Genap untuk seluruh kelas X, XI, dan XII.',
                'category' => 'Akademik',
                'file_path' => 'downloads/jadwal-uas-genap-2024-2025.pdf',
                'original_filename' => 'Jadwal-UAS-Genap-2024-2025.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 307200,
                'download_count' => 0,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Pengumuman Hasil SPMB Tahap 1',
                'description' => 'Pengumuman resmi hasil seleksi SPMB tahap pertama jalur Prestasi dan Afirmasi tahun ajaran 2026/2027.',
                'category' => 'Pengumuman',
                'file_path' => 'downloads/pengumuman-spmb-tahap-1.pdf',
                'original_filename' => 'Pengumuman-SPMB-Tahap-1.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 262144,
                'download_count' => 0,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Formulir Permohonan Surat Keterangan Aktif',
                'description' => 'Formulir untuk mengajukan permohonan Surat Keterangan Aktif Sekolah bagi siswa yang membutuhkan.',
                'category' => 'Formulir',
                'file_path' => 'downloads/formulir-surat-keterangan-aktif.pdf',
                'original_filename' => 'Formulir-Surat-Keterangan-Aktif.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 204800,
                'download_count' => 0,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Panduan Penggunaan E-Learning Sekolah',
                'description' => 'Panduan lengkap cara mengakses dan menggunakan platform e-learning untuk mendukung proses pembelajaran.',
                'category' => 'Administrasi',
                'file_path' => 'downloads/panduan-elearning.pdf',
                'original_filename' => 'Panduan-E-Learning.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 1048576,
                'download_count' => 0,
                'sort_order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($downloads as $data) {
            Download::create($data);
        }
    }
}
