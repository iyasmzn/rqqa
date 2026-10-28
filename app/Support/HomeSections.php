<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Seksi bawaan halaman depan — sumber tunggal bagi welcome.blade.php (urutan
 * bawaan, partial, fitur pengait) dan panel Pengaturan Halaman Depan (label
 * kartu, ID anchor). Urutan & visibilitas pilihan admin disimpan di setting
 * `section_order`.
 */
class HomeSections
{
    /**
     * Urutan di sini adalah urutan bawaan. `anchor` harus sama persis dengan
     * atribut `id` pada partialnya, sebab admin memakainya sebagai tujuan
     * tautan menu (`#profil`). `feature` menandai seksi yang ikut padam bila
     * fitur itu dimatikan di menu Fitur Website.
     *
     * @var array<string, array{label: string, partial: string, anchor: string, feature?: string}>
     */
    private const SECTIONS = [
        'section_hero' => ['label' => '🖼️  Hero Image Slider', 'partial' => 'sections.hero', 'anchor' => 'beranda'],
        'section_quick_links' => ['label' => '🔗  Tautan Cepat', 'partial' => 'sections.quick-links', 'anchor' => 'tautan-cepat'],
        'section_spmb' => ['label' => '📋  Kartu SPMB', 'partial' => 'sections.spmb', 'anchor' => 'spmb'],
        'section_stats' => ['label' => '📊  Statistik Sekolah', 'partial' => 'sections.stats', 'anchor' => 'profil'],
        'section_principal' => ['label' => '👨‍💼  Sambutan Para Tokoh', 'partial' => 'sections.principal', 'anchor' => 'sambutan'],
        'section_spmb_steps' => ['label' => '📝  Tahapan SPMB', 'partial' => 'sections.spmb-steps', 'anchor' => 'tahapan-spmb'],
        'section_programs' => ['label' => '🎓  Program Unggulan', 'partial' => 'sections.programs', 'anchor' => 'program'],
        'section_events' => ['label' => '📅  Agenda Kegiatan', 'partial' => 'sections.events', 'anchor' => 'kegiatan-upcoming'],
        'section_books' => ['label' => '📚  Buku', 'partial' => 'sections.books', 'anchor' => 'toko-buku', 'feature' => 'toko'],
        'section_gallery' => ['label' => '🖼️  Galeri Foto', 'partial' => 'sections.gallery', 'anchor' => 'galeri'],
        'section_blog' => ['label' => '📰  Blog & Berita', 'partial' => 'sections.blog', 'anchor' => 'blog'],
        'section_testimonials' => ['label' => '💬  Kesan & Pesan Alumni', 'partial' => 'sections.testimonials', 'anchor' => 'kesan-pesan'],
        'section_donasi' => ['label' => '💝  Donasi', 'partial' => 'sections.donasi', 'anchor' => 'donasi', 'feature' => 'donasi'],
        'section_contact' => ['label' => '📞  Kontak Kami', 'partial' => 'sections.contact', 'anchor' => 'kontak-section'],
    ];

    /**
     * Nama fitur seperti tertulis di menu Fitur Website.
     *
     * @var array<string, string>
     */
    private const FEATURE_LABELS = [
        'toko' => 'Toko Buku',
        'donasi' => 'Donasi',
    ];

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::SECTIONS);
    }

    public static function exists(string $key): bool
    {
        return isset(self::SECTIONS[$key]);
    }

    public static function label(string $key): string
    {
        return self::SECTIONS[$key]['label'] ?? $key;
    }

    public static function anchor(string $key): string
    {
        return self::SECTIONS[$key]['anchor'] ?? '';
    }

    public static function partial(string $key): ?string
    {
        return self::SECTIONS[$key]['partial'] ?? null;
    }

    /**
     * Nama fitur yang sedang dimatikan dan ikut menyembunyikan seksi ini,
     * atau null bila seksinya tidak terhalang fitur apa pun.
     */
    public static function disabledFeatureLabel(string $key): ?string
    {
        $feature = self::SECTIONS[$key]['feature'] ?? null;

        if ($feature === null || feature_enabled($feature)) {
            return null;
        }

        return self::FEATURE_LABELS[$feature] ?? $feature;
    }

    /**
     * Urutan & visibilitas tersimpan yang sudah dirapikan: kunci tak dikenal
     * dan duplikat dibuang, lalu seksi yang ditambahkan setelah urutan itu
     * disimpan ikut di belakang (dalam urutan bawaan) agar tetap tampil.
     *
     * @return list<array{key: string, visible: bool}>
     */
    public static function order(): array
    {
        $saved = json_decode((string) Setting::get('section_order', ''), true);
        $order = [];

        foreach (is_array($saved) ? $saved : [] as $section) {
            $key = is_array($section) ? ($section['key'] ?? null) : null;

            if (! is_string($key) || ! self::exists($key) || isset($order[$key])) {
                continue;
            }

            $order[$key] = ['key' => $key, 'visible' => (bool) ($section['visible'] ?? true)];
        }

        foreach (self::keys() as $key) {
            $order[$key] ??= ['key' => $key, 'visible' => true];
        }

        return array_values($order);
    }

    /**
     * Partial yang benar-benar dirender halaman depan, berurutan — seksi yang
     * disembunyikan admin atau fiturnya sedang dimatikan dilewati.
     *
     * @return list<string>
     */
    public static function visiblePartials(): array
    {
        $partials = [];

        foreach (self::order() as $section) {
            if ($section['visible'] && self::disabledFeatureLabel($section['key']) === null) {
                $partials[] = self::SECTIONS[$section['key']]['partial'];
            }
        }

        return $partials;
    }
}
