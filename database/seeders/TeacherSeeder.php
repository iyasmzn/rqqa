<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        if (Teacher::exists()) {
            return;
        }

        $teachers = [
            [
                'name' => 'Drs. Ahmad Fauzi, M.Pd.',
                'nip' => '197601012005011001',
                'position' => 'Kepala Sekolah',
                'subject' => 'Manajemen Pendidikan',
                'education' => 'S2 Manajemen Pendidikan',
                'phone' => '(022) 1234-5678',
                'email' => 'ahmad.fauzi@pesantren.sch.id',
                'whatsapp' => '6281234567890',
                'sort_order' => 1,
            ],
            [
                'name' => 'Dra. Siti Rahayu, M.Pd.',
                'nip' => '197803152003012002',
                'position' => 'Wakil Kepala Sekolah Bidang Kurikulum',
                'subject' => 'Bahasa Indonesia',
                'education' => 'S2 Pendidikan Bahasa Indonesia',
                'phone' => '(022) 1234-5679',
                'email' => 'siti.rahayu@pesantren.sch.id',
                'whatsapp' => '6281234567891',
                'sort_order' => 2,
            ],
            [
                'name' => 'Budi Santoso, S.Pd.',
                'nip' => '198205102009011003',
                'position' => 'Guru Matematika',
                'subject' => 'Matematika',
                'education' => 'S1 Pendidikan Matematika',
                'phone' => null,
                'email' => 'budi.santoso@pesantren.sch.id',
                'whatsapp' => '6281234567892',
                'sort_order' => 3,
            ],
            [
                'name' => 'Dewi Lestari, S.Pd., M.Si.',
                'nip' => '198407222010012004',
                'position' => 'Guru Fisika',
                'subject' => 'Fisika',
                'education' => 'S2 Pendidikan Fisika',
                'phone' => null,
                'email' => 'dewi.lestari@pesantren.sch.id',
                'whatsapp' => '6281234567893',
                'sort_order' => 4,
            ],
            [
                'name' => 'Hendra Gunawan, S.Pd.',
                'nip' => '198601052011011005',
                'position' => 'Guru Kimia',
                'subject' => 'Kimia',
                'education' => 'S1 Pendidikan Kimia',
                'phone' => null,
                'email' => 'hendra.gunawan@pesantren.sch.id',
                'whatsapp' => null,
                'sort_order' => 5,
            ],
            [
                'name' => 'Rina Marlina, S.Pd.',
                'nip' => '198902142012012006',
                'position' => 'Guru Biologi',
                'subject' => 'Biologi',
                'education' => 'S1 Pendidikan Biologi',
                'phone' => null,
                'email' => 'rina.marlina@pesantren.sch.id',
                'whatsapp' => '6281234567895',
                'sort_order' => 6,
            ],
            [
                'name' => 'Agus Permana, S.Pd.',
                'nip' => '198511302013011007',
                'position' => 'Guru Bahasa Inggris',
                'subject' => 'Bahasa Inggris',
                'education' => 'S1 Pendidikan Bahasa Inggris',
                'phone' => null,
                'email' => 'agus.permana@pesantren.sch.id',
                'whatsapp' => '6281234567896',
                'sort_order' => 7,
            ],
            [
                'name' => 'Nurul Hidayah, S.Pd.I.',
                'nip' => '199003082014012008',
                'position' => 'Guru Pendidikan Agama Islam',
                'subject' => 'Pendidikan Agama Islam',
                'education' => 'S1 Pendidikan Agama Islam',
                'phone' => null,
                'email' => 'nurul.hidayah@pesantren.sch.id',
                'whatsapp' => '6281234567897',
                'sort_order' => 8,
            ],
            [
                'name' => 'Drs. Yusuf Hamdani',
                'nip' => '197209171998011009',
                'position' => 'Guru Sejarah',
                'subject' => 'Sejarah',
                'education' => 'S1 Pendidikan Sejarah',
                'phone' => null,
                'email' => null,
                'whatsapp' => null,
                'sort_order' => 9,
            ],
            [
                'name' => 'Fitria Handayani, S.Pd.',
                'nip' => '199205192015012010',
                'position' => 'Guru Ekonomi',
                'subject' => 'Ekonomi',
                'education' => 'S1 Pendidikan Ekonomi',
                'phone' => null,
                'email' => 'fitria.handayani@pesantren.sch.id',
                'whatsapp' => '6281234567899',
                'sort_order' => 10,
            ],
            [
                'name' => 'Drs. Bambang Sulistyo',
                'nip' => '196812051994031011',
                'position' => 'Guru Pendidikan Jasmani',
                'subject' => 'Penjasorkes',
                'education' => 'S1 Pendidikan Jasmani',
                'phone' => null,
                'email' => null,
                'whatsapp' => null,
                'sort_order' => 11,
            ],
            [
                'name' => 'Maya Sari, S.Kom., M.Pd.',
                'nip' => '199108242016012012',
                'position' => 'Guru TIK',
                'subject' => 'Teknologi Informasi dan Komunikasi',
                'education' => 'S2 Manajemen Pendidikan',
                'phone' => null,
                'email' => 'maya.sari@pesantren.sch.id',
                'whatsapp' => '6281234568001',
                'sort_order' => 12,
            ],
        ];

        foreach ($teachers as $data) {
            Teacher::create(array_merge($data, ['is_active' => true]));
        }
    }
}
