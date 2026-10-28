<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Support\HomeSections;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeSectionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_falls_back_to_the_default_order(): void
    {
        $order = HomeSections::order();

        $this->assertSame(HomeSections::keys(), array_column($order, 'key'));
        $this->assertNotContains(false, array_column($order, 'visible'));
    }

    public function test_order_drops_unknown_and_duplicate_keys_and_appends_missing_sections(): void
    {
        Setting::set('section_order', json_encode([
            ['key' => 'section_blog', 'visible' => false],
            ['key' => 'section_unknown', 'visible' => true],
            ['key' => 'section_blog', 'visible' => true],
            'bukan-seksi',
            ['key' => 'section_programs'],
        ]));

        $order = HomeSections::order();
        $keys = array_column($order, 'key');

        $this->assertSame(['section_blog', 'section_programs', 'section_hero'], array_slice($keys, 0, 3));
        $this->assertCount(count(HomeSections::keys()), $order);
        $this->assertSame($keys, array_unique($keys));
        $this->assertFalse($order[0]['visible']);
        $this->assertTrue($order[1]['visible']);
    }

    public function test_every_section_anchor_exists_in_its_partial(): void
    {
        foreach (HomeSections::keys() as $key) {
            $partial = resource_path('views/'.str_replace('.', '/', (string) HomeSections::partial($key)).'.blade.php');

            $this->assertFileExists($partial);
            $this->assertStringContainsString(
                'id="'.HomeSections::anchor($key).'"',
                (string) file_get_contents($partial),
                "ID anchor seksi {$key} tidak ada di partialnya.",
            );
        }
    }

    public function test_home_page_renders_sections_in_the_saved_order_and_skips_hidden_ones(): void
    {
        Setting::set('section_order', json_encode([
            ['key' => 'section_blog', 'visible' => true],
            ['key' => 'section_programs', 'visible' => true],
            ['key' => 'section_events', 'visible' => false],
        ]));

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['id="blog"', 'id="program"', 'id="beranda"'], false)
            ->assertDontSee('id="kegiatan-upcoming"', false);
    }

    public function test_home_page_skips_a_section_whose_feature_is_off(): void
    {
        $this->get(route('home'))->assertSee('id="toko-buku"', false);

        Setting::set('feature_toko', false);

        $this->assertSame('Toko Buku', HomeSections::disabledFeatureLabel('section_books'));
        $this->assertNull(HomeSections::disabledFeatureLabel('section_blog'));

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('id="toko-buku"', false);
    }
}
