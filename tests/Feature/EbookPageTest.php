<?php

namespace Tests\Feature;

use App\Models\Ebook;
use App\Models\Setting;
use App\Support\HomeSections;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EbookPageTest extends TestCase
{
    use RefreshDatabase;

    // ── Halaman daftar ─────────────────────────────────────────────

    public function test_index_lists_only_published_ebooks(): void
    {
        Ebook::factory()->create(['title' => 'Tuntunan Shalat Lengkap']);
        Ebook::factory()->unpublished()->create(['title' => 'Draf Belum Terbit']);

        $this->get(route('ebooks.index'))
            ->assertOk()
            ->assertSee('Tuntunan Shalat Lengkap')
            ->assertDontSee('Draf Belum Terbit');
    }

    public function test_index_searches_title_and_author(): void
    {
        Ebook::factory()->create(['title' => 'Pengantar Ilmu Tajwid', 'author' => 'Ustadz Ahmad']);
        Ebook::factory()->create(['title' => 'Matematika Kelas 7', 'author' => 'Bu Sari']);

        $this->get(route('ebooks.index', ['q' => 'tajwid']))
            ->assertOk()
            ->assertSee('Pengantar Ilmu Tajwid')
            ->assertDontSee('Matematika Kelas 7');

        $this->get(route('ebooks.index', ['q' => 'Bu Sari']))
            ->assertOk()
            ->assertSee('Matematika Kelas 7')
            ->assertDontSee('Pengantar Ilmu Tajwid');
    }

    public function test_index_filters_by_category_and_format(): void
    {
        Ebook::factory()->pdf()->create(['title' => 'Ringkasan Adab', 'category' => 'Kitab']);
        Ebook::factory()->link()->create(['title' => 'Modul Daring', 'category' => 'Modul']);

        $this->get(route('ebooks.index', ['category' => 'Kitab']))
            ->assertOk()
            ->assertSee('Ringkasan Adab')
            ->assertDontSee('Modul Daring');

        $this->get(route('ebooks.index', ['format' => Ebook::SOURCE_LINK]))
            ->assertOk()
            ->assertSee('Modul Daring')
            ->assertDontSee('Ringkasan Adab');
    }

    public function test_index_ignores_unknown_and_array_query_values(): void
    {
        Ebook::factory()->create(['title' => 'Bahasa Arab untuk Pemula']);

        $this->get('/ebook?format=docx&sort=acak&q[]=x&category[]=y')
            ->assertOk()
            ->assertSee('Bahasa Arab untuk Pemula');
    }

    // ── Halaman detail ─────────────────────────────────────────────

    public function test_pdf_ebook_page_shows_the_reader_and_the_download(): void
    {
        $ebook = Ebook::factory()->pdf()->create([
            'title' => 'Panduan Belajar Mandiri',
            'original_filename' => 'panduan-belajar.pdf',
            'file_size' => 2_621_440,
        ]);

        $this->get(route('ebooks.show', $ebook))
            ->assertOk()
            ->assertSee('id="baca"', false)
            ->assertSee($ebook->file_url.'#view=FitH', false)
            ->assertSee('download="panduan-belajar.pdf"', false)
            ->assertSee('PDF · 2.5 MB');
    }

    public function test_download_button_is_hidden_when_downloads_are_disabled(): void
    {
        $ebook = Ebook::factory()->pdf()->create(['allow_download' => false]);

        $this->get(route('ebooks.show', $ebook))
            ->assertOk()
            ->assertSee('id="baca"', false)
            ->assertDontSee('download="'.$ebook->download_filename.'"', false)
            ->assertDontSee('Unduh PDF');
    }

    public function test_link_ebook_page_opens_the_external_site(): void
    {
        $ebook = Ebook::factory()->link('https://www.gutenberg.org/ebooks/11')->create();

        $this->get(route('ebooks.show', $ebook))
            ->assertOk()
            ->assertSee('href="https://www.gutenberg.org/ebooks/11" target="_blank"', false)
            ->assertSee('Buka E-Book')
            ->assertSee('gutenberg.org')
            ->assertDontSee('id="baca"', false);
    }

    public function test_embed_ebook_page_shows_the_viewer_in_the_page(): void
    {
        $ebook = Ebook::factory()->embed('https://heyzine.com/flip-book/contoh.html')->create();

        $this->get(route('ebooks.show', $ebook))
            ->assertOk()
            ->assertSee('id="baca"', false)
            ->assertSee('<iframe src="https://heyzine.com/flip-book/contoh.html"', false);
    }

    public function test_ebook_without_cover_image_gets_a_generated_cover(): void
    {
        $ebook = Ebook::factory()->create(['title' => 'Adab Penuntut Ilmu', 'cover_image' => null]);

        $this->get(route('ebooks.show', $ebook))
            ->assertOk()
            ->assertSee('ebook-cover__generated', false)
            ->assertSee('aria-label="Sampul Adab Penuntut Ilmu"', false);
    }

    public function test_related_ebooks_come_from_the_same_category(): void
    {
        $ebook = Ebook::factory()->create(['category' => 'Kitab']);
        Ebook::factory()->create(['title' => 'Kitab Serupa', 'category' => 'Kitab']);
        Ebook::factory()->create(['title' => 'Modul Berbeda', 'category' => 'Modul']);

        $this->get(route('ebooks.show', $ebook))
            ->assertOk()
            ->assertSee('E-Book Lainnya')
            ->assertSee('Kitab Serupa')
            ->assertDontSee('Modul Berbeda');
    }

    public function test_unpublished_ebook_returns_not_found(): void
    {
        $ebook = Ebook::factory()->unpublished()->create();

        $this->get(route('ebooks.show', $ebook))->assertNotFound();
    }

    // ── Seksi halaman depan ────────────────────────────────────────

    public function test_home_section_shows_only_featured_ebooks(): void
    {
        Ebook::factory()->featured()->create(['title' => 'Pilihan Redaksi']);
        Ebook::factory()->create(['title' => 'Koleksi Biasa']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="ebook"', false)
            ->assertSee('E-Book Pilihan')
            ->assertSee('Pilihan Redaksi')
            ->assertDontSee('Koleksi Biasa');
    }

    public function test_home_section_falls_back_to_the_latest_ebooks_without_featured_ones(): void
    {
        Ebook::factory()->create(['title' => 'Terbitan Terbaru']);
        Ebook::factory()->unpublished()->featured()->create(['title' => 'Unggulan Belum Terbit']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Terbitan Terbaru')
            ->assertDontSee('Unggulan Belum Terbit');
    }

    public function test_home_section_is_skipped_without_published_ebooks(): void
    {
        Ebook::factory()->unpublished()->create();

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('id="ebook"', false);
    }

    public function test_home_section_is_skipped_when_the_feature_is_disabled(): void
    {
        Ebook::factory()->featured()->create();
        Setting::set('feature_ebook', false);

        $this->assertSame('E-Book', HomeSections::disabledFeatureLabel('section_ebooks'));

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('id="ebook"', false);
    }

    public function test_home_section_uses_the_custom_texts(): void
    {
        Ebook::factory()->featured()->create();

        Setting::setMany([
            'section_ebooks_eyebrow' => 'Pojok Baca',
            'section_ebooks_title' => 'Rak Buku Digital',
            'section_ebooks_subtitle' => '',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Pojok Baca')
            ->assertSee('Rak Buku Digital')
            ->assertDontSee('Buku pelajaran, modul, dan bacaan bermanfaat yang bisa dibaca langsung secara gratis.');
    }
}
