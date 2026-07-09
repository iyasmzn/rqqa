<?php

namespace Database\Seeders;

use App\Models\Greeting;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class GreetingSeeder extends Seeder
{
    public function run(): void
    {
        if (Greeting::exists()) {
            return;
        }

        // Migrasi data lama dari pengaturan kepala sekolah (settings principal_*).
        $legacyName = Setting::get('principal_name');

        if ($legacyName) {
            Greeting::create([
                'name' => $legacyName,
                'position' => Setting::get('principal_title', 'Kepala Sekolah'),
                'nip' => Setting::get('principal_nip'),
                'photo' => Setting::get('principal_photo'),
                'excerpt' => Setting::get('principal_excerpt'),
                'page_slug' => Setting::get('principal_page'),
                'is_published' => true,
                'sort_order' => 1,
            ]);

            return;
        }

        $greetings = [
            [
                'name' => 'KH. Abdullah Mubarok, Lc., M.A.',
                'position' => 'Kepala Yayasan',
                'nip' => null,
                'photo' => null,
                'excerpt' => "Bismillahirrahmanirrahim. Alhamdulillah, atas izin dan ridha Allah SWT, Pondok Pesantren Qurrota A'yun terus berkembang menjadi lembaga pendidikan Islam yang amanah dan terpercaya.\n\nKami berkomitmen untuk mencetak generasi Qurani yang tidak hanya hafal Al-Qur'an, tetapi juga berilmu, berakhlak mulia, dan siap menghadapi tantangan zaman. Bersama seluruh asatidz dan keluarga besar pesantren, kami terus berijtihad demi masa depan santri yang gemilang di dunia dan akhirat.",
                'page_slug' => 'sambutan-kepala-yayasan',
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Ust. Muhammad Ridwan, S.Pd.I.',
                'position' => 'Kepala Sekolah',
                'nip' => null,
                'photo' => null,
                'excerpt' => "Assalamu'alaikum Warahmatullahi Wabarakatuh. Selamat datang di website resmi kami. Melalui media ini, kami berharap dapat menjalin komunikasi yang baik dengan wali santri dan masyarakat luas.\n\nKami terus berupaya menghadirkan lingkungan belajar yang kondusif, modern, dan islami demi masa depan para santri.",
                'page_slug' => null,
                'is_published' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($greetings as $greeting) {
            Greeting::create($greeting);
        }
    }
}
