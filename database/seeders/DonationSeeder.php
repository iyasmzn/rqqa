<?php

namespace Database\Seeders;

use App\Models\Donation;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        Donation::factory()->count(5)->pending()->create();
        Donation::factory()->count(20)->confirmed()->create();
        Donation::factory()->count(3)->create(['status' => 'rejected']);
    }
}
