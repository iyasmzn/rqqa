<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed semua settings default aplikasi.
     * Menggunakan firstOrCreate agar tidak menimpa data yang sudah diubah admin.
     */
    public function run(): void
    {
        $defaults = $this->defaults();

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // Pastikan section_donasi selalu ada di section_order,
        // meski setting sudah tersimpan sebelumnya di DB.
        $this->ensureSectionDonasiInOrder();
    }

    /**
     * Inject section_donasi ke dalam section_order yang sudah tersimpan
     * di database, sebelum section_contact. Idempoten — aman dijalankan ulang.
     */
    private function ensureSectionDonasiInOrder(): void
    {
        $raw = Setting::get('section_order', '[]');
        $order = json_decode($raw, true) ?: [];

        $hasDonasi = collect($order)->contains('key', 'section_donasi');

        if ($hasDonasi) {
            return;
        }

        $contactIdx = collect($order)->search(fn ($s) => $s['key'] === 'section_contact');

        if ($contactIdx !== false) {
            array_splice($order, $contactIdx, 0, [['key' => 'section_donasi', 'visible' => true]]);
        } else {
            $order[] = ['key' => 'section_donasi', 'visible' => true];
        }

        Setting::set('section_order', json_encode($order));
    }

    /** @return array<string, mixed> */
    private function defaults(): array
    {
        return array_merge(
            $this->general(),
            $this->navbar(),
            $this->landingPage(),
            $this->quickLinks(),
            $this->spmb(),
            $this->theme(),
            $this->donasi(),
        );
    }

    // ── Pengaturan Umum ──────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function general(): array
    {
        return [
            'site_name' => 'Pondok Pesantren',
            'site_tagline' => 'Membentuk Generasi Qurani',
            'site_description' => 'Website resmi Pondok Pesantren. Informasi penerimaan santri baru, program pendidikan, kegiatan, dan cerita inspiratif dari pesantren.',

            'contact_address' => 'Jl. Pendidikan No. 1, Kota Anda',
            'contact_phone' => '(021) 123-4567',
            'contact_email' => 'info@pesantren.sch.id',
            'contact_hours' => 'Senin–Sabtu, 07.00–17.00 WIB',
            'contact_map_url' => null,

            'social_facebook' => null,
            'social_instagram' => null,
            'social_youtube' => null,
            'social_whatsapp' => null,

            // Nomor WA khusus untuk pesanan buku (toko)
            'shop_whatsapp' => null,
        ];
    }

    // ── Menu Navigasi ────────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function navbar(): array
    {
        return [
            'nav_items' => json_encode([
                ['label' => 'Beranda',  'url' => '/',              'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Profil',   'url' => '#sambutan',      'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Program',  'url' => '/program',       'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Kegiatan', 'url' => '/kegiatan',      'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Cerita',   'url' => '/cerita-santri', 'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Blog',     'url' => '/blog',          'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Kontak',   'url' => '#kontak',        'target' => '_self', 'is_active' => true, 'children' => []],
            ]),
        ];
    }

    // ── Urutan Seksi Halaman Depan ───────────────────────────────────

    /** @return array<string, mixed> */
    private function landingPage(): array
    {
        return [
            'section_order' => json_encode([
                ['key' => 'section_hero',        'visible' => true],
                ['key' => 'section_stats',       'visible' => true],
                ['key' => 'section_principal',   'visible' => true],
                ['key' => 'section_programs',    'visible' => true],
                ['key' => 'section_events',      'visible' => true],
                ['key' => 'section_books',       'visible' => true],
                ['key' => 'section_spmb',        'visible' => true],
                ['key' => 'section_spmb_steps',  'visible' => true],
                ['key' => 'section_blog',        'visible' => true],
                ['key' => 'section_donasi',      'visible' => true],
                ['key' => 'section_contact',     'visible' => true],
            ]),
        ];
    }

    // ── Tautan Cepat ─────────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function quickLinks(): array
    {
        return [
            'quick_links' => json_encode([
                ['icon' => '📋', 'label' => 'Daftar Santri', 'url' => '#spmb',          'is_active' => true],
                ['icon' => '📚', 'label' => 'Buku',          'url' => '/buku',          'is_active' => true],
                ['icon' => '🎓', 'label' => 'Program',       'url' => '/program',       'is_active' => true],
                ['icon' => '🗓', 'label' => 'Kegiatan',      'url' => '/kegiatan',      'is_active' => true],
                ['icon' => '📖', 'label' => 'Cerita Santri', 'url' => '/cerita-santri', 'is_active' => true],
                ['icon' => '📞', 'label' => 'Kontak',        'url' => '#kontak',        'is_active' => true],
            ]),
        ];
    }

    // ── PPDB / Penerimaan Santri ─────────────────────────────────────

    /** @return array<string, mixed> */
    private function spmb(): array
    {
        $procedures = [
            ['icon' => '📝', 'title' => 'Isi Formulir Online',  'description' => 'Kunjungi halaman pendaftaran dan isi formulir dengan data calon santri secara lengkap dan benar.'],
            ['icon' => '📁', 'title' => 'Siapkan Berkas',       'description' => 'Persiapkan dokumen: fotokopi KK, akta lahir, ijazah/SKHU, rapor, dan pas foto terbaru calon santri.'],
            ['icon' => '✅', 'title' => 'Verifikasi & Tes',     'description' => 'Datang ke pesantren untuk verifikasi berkas dan mengikuti tes baca Al-Qur\'an serta wawancara.'],
            ['icon' => '🎉', 'title' => 'Pengumuman Hasil',     'description' => 'Hasil penerimaan diumumkan melalui website resmi dan dihubungi langsung oleh panitia via WhatsApp.'],
        ];

        $fees = [
            ['category' => 'Biaya Pendaftaran', 'amount' => 'Rp 100.000',   'note' => 'Tidak dikembalikan'],
            ['category' => 'Biaya Masuk',       'amount' => 'Rp 2.500.000', 'note' => 'Termasuk seragam & perlengkapan'],
            ['category' => 'SPP Bulanan',       'amount' => 'Rp 750.000',   'note' => 'Sudah termasuk makan 3x sehari'],
            ['category' => 'Kitab & Buku',      'amount' => 'Rp 350.000',   'note' => 'Per semester, sesuai tingkatan'],
        ];

        return [
            // Kartu di halaman depan
            'spmb_card_title' => 'Penerimaan Santri Baru {year} Dibuka!',
            'spmb_card_description' => 'Pondok Pesantren membuka penerimaan santri baru. Tersedia program Tahfidz, Diniyah, dan Terpadu. Daftarkan putra-putri Anda sebelum batas waktu.',
            'spmb_card_cta_label' => 'Daftar Sekarang',
            'spmb_card_cta_url' => '/ppdb',
            'spmb_card_secondary_label' => 'Info Selengkapnya',

            // Section tahapan di halaman depan
            'spmb_steps_title' => 'Alur Pendaftaran Santri',
            'spmb_steps_description' => 'Ikuti langkah-langkah berikut untuk mendaftarkan putra-putri Anda sebagai santri baru.',
            'spmb_steps_cta_label' => 'Lihat Detail & Daftar',
            'spmb_steps_cta_url' => '/ppdb',

            // Form pendaftaran
            'spmb_form_enabled' => 1,
            'spmb_form_title' => 'Formulir Pendaftaran Santri Baru',
            'spmb_form_description' => 'Isi formulir di bawah ini dengan data yang benar dan lengkap. Panitia akan menghubungi Anda untuk proses verifikasi dan jadwal tes.',
            'spmb_closed_message' => 'Pendaftaran santri baru saat ini sedang ditutup. Pantau informasi terbaru melalui halaman ini atau hubungi panitia via WhatsApp.',

            // Konten prosedur & biaya
            'spmb_procedures' => json_encode($procedures),
            'spmb_fees' => json_encode($fees),
        ];
    }

    // ── Donasi ────────────────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function donasi(): array
    {
        return [
            'donasi_bank_name' => 'Bank Syariah Indonesia (BSI)',
            'donasi_bank_account' => '7123456789',
            'donasi_bank_holder' => 'Pondok Pesantren',
        ];
    }

    // ── Tema & Tampilan ───────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function theme(): array
    {
        return [
            'theme_primary_color' => '#08484A',
            'theme_font' => 'plus-jakarta-sans',
        ];
    }
}
