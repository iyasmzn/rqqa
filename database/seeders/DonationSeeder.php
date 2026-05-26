<?php

namespace Database\Seeders;

use App\Models\Donation;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        if (Donation::exists()) {
            return;
        }

        $donations = [
            [
                'name' => 'H. Abdurrahman Wahid',
                'email' => 'abdurrahman@gmail.com',
                'phone' => '081234567890',
                'is_anonymous' => false,
                'amount' => 1000000,
                'payment_method' => 'transfer_bank',
                'message' => 'Semoga menjadi amal jariyah yang terus mengalir. Aamiin.',
                'status' => 'confirmed',
                'confirmed_at' => now()->subDays(30),
            ],
            [
                'name' => 'Hj. Fatimah Az-Zahra',
                'email' => null,
                'phone' => '082345678901',
                'is_anonymous' => false,
                'amount' => 500000,
                'payment_method' => 'transfer_bank',
                'message' => 'Lillahi ta\'ala, semoga bermanfaat untuk para santri.',
                'status' => 'confirmed',
                'confirmed_at' => now()->subDays(25),
            ],
            [
                'name' => 'Anonim',
                'email' => null,
                'phone' => null,
                'is_anonymous' => true,
                'amount' => 250000,
                'payment_method' => 'qris',
                'message' => 'Jazakumullah khairan.',
                'status' => 'confirmed',
                'confirmed_at' => now()->subDays(20),
            ],
            [
                'name' => 'Bapak Muhamad Rizki',
                'email' => 'mrizki@yahoo.com',
                'phone' => '083456789012',
                'is_anonymous' => false,
                'amount' => 200000,
                'payment_method' => 'transfer_bank',
                'message' => 'Semoga pesantren semakin maju dan melahirkan generasi qurani.',
                'status' => 'confirmed',
                'confirmed_at' => now()->subDays(18),
            ],
            [
                'name' => 'Alumni Angkatan 2015',
                'email' => 'alumni2015@gmail.com',
                'phone' => '084567890123',
                'is_anonymous' => false,
                'amount' => 150000,
                'payment_method' => 'qris',
                'message' => 'Terimakasih atas ilmu yang telah diajarkan. Ini bakti kami.',
                'status' => 'confirmed',
                'confirmed_at' => now()->subDays(15),
            ],
            [
                'name' => 'Dra. Siti Aminah',
                'email' => 'sitaminah@gmail.com',
                'phone' => '085678901234',
                'is_anonymous' => false,
                'amount' => 500000,
                'payment_method' => 'tunai',
                'message' => null,
                'status' => 'confirmed',
                'confirmed_at' => now()->subDays(12),
            ],
            [
                'name' => 'Keluarga Besar Pak Hasan',
                'email' => null,
                'phone' => '086789012345',
                'is_anonymous' => false,
                'amount' => 2000000,
                'payment_method' => 'transfer_bank',
                'message' => 'Donasi untuk pembangunan perpustakaan pesantren.',
                'status' => 'confirmed',
                'confirmed_at' => now()->subDays(10),
            ],
            [
                'name' => 'Anonim',
                'email' => null,
                'phone' => null,
                'is_anonymous' => true,
                'amount' => 100000,
                'payment_method' => 'qris',
                'message' => 'Semoga barokah.',
                'status' => 'confirmed',
                'confirmed_at' => now()->subDays(8),
            ],
            [
                'name' => 'Ustadz Fulan bin Fulan',
                'email' => 'ustadzfulan@gmail.com',
                'phone' => '087890123456',
                'is_anonymous' => false,
                'amount' => 300000,
                'payment_method' => 'transfer_bank',
                'message' => 'Untuk beasiswa santri yang tidak mampu.',
                'status' => 'confirmed',
                'confirmed_at' => now()->subDays(5),
            ],
            [
                'name' => 'Ibu Nurhasanah',
                'email' => 'nurhasanah@gmail.com',
                'phone' => '088901234567',
                'is_anonymous' => false,
                'amount' => 150000,
                'payment_method' => 'qris',
                'message' => null,
                'status' => 'pending',
                'confirmed_at' => null,
            ],
            [
                'name' => 'Bapak Wahyu Prasetyo',
                'email' => 'wahyuprasetyo@gmail.com',
                'phone' => '089012345678',
                'is_anonymous' => false,
                'amount' => 250000,
                'payment_method' => 'transfer_bank',
                'message' => 'Semoga dibalas kebaikan berlipat ganda.',
                'status' => 'pending',
                'confirmed_at' => null,
            ],
            [
                'name' => 'Anonim',
                'email' => null,
                'phone' => '081298765432',
                'is_anonymous' => true,
                'amount' => 50000,
                'payment_method' => 'tunai',
                'message' => null,
                'status' => 'pending',
                'confirmed_at' => null,
            ],
        ];

        foreach ($donations as $data) {
            Donation::create($data);
        }
    }
}
