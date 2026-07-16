<?php

namespace Database\Seeders;

use App\Models\ContactItem;
use Illuminate\Database\Seeder;

class ContactItemSeeder extends Seeder
{
    public function run(): void
    {
        if (ContactItem::exists()) {
            return;
        }

        $items = [
            [
                'icon' => '📍',
                'label' => 'Alamat',
                'value' => 'Jl. Pendidikan No. 1, Kota Bandung 40111',
                'link' => 'https://maps.google.com/?q=Jl+Pendidikan+No+1+Bandung',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'icon' => '📞',
                'label' => 'Telepon',
                'value' => '(022) 1234-5678',
                'link' => 'tel:+62221234567',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'icon' => '✉️',
                'label' => 'Email',
                'value' => 'info@pesantren.sch.id',
                'link' => 'mailto:info@pesantren.sch.id',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'icon' => '💬',
                'label' => 'WhatsApp',
                'value' => '+62 812-3456-7890',
                'link' => 'https://wa.me/6281234567890',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'icon' => '🕐',
                'label' => 'Jam Operasional',
                'value' => 'Senin–Jumat, 07.00–15.30 WIB',
                'link' => null,
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            ContactItem::create($item);
        }
    }
}
