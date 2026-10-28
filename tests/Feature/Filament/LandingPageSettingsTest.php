<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\LandingPageSettings;
use App\Filament\Resources\Slides\SlideResource;
use App\Models\Setting;
use App\Models\User;
use App\Support\HomeSections;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class LandingPageSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::firstOrCreate(['name' => 'View:LandingPageSettings', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'ViewAny:Slide', 'guard_name' => 'web']);

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web'])
            ->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'author', 'guard_name' => 'web']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    private function admin(): User
    {
        return User::factory()->create()->assignRole('super_admin');
    }

    public function test_super_admin_can_open_the_page(): void
    {
        $this->actingAs($this->admin())
            ->get(LandingPageSettings::getUrl())
            ->assertSuccessful();
    }

    public function test_author_cannot_open_the_page(): void
    {
        $author = User::factory()->create()->assignRole('author');

        $this->actingAs($author)
            ->get(LandingPageSettings::getUrl())
            ->assertForbidden();
    }

    public function test_every_home_section_is_a_card_in_the_saved_order(): void
    {
        Setting::set('section_order', json_encode([
            ['key' => 'section_blog', 'visible' => false],
            ['key' => 'section_unknown', 'visible' => true],
            ['key' => 'section_programs', 'visible' => true],
        ]));

        $sections = collect(Livewire::actingAs($this->admin())->test(LandingPageSettings::class)->get('data.sections'))->values();

        $this->assertCount(count(HomeSections::keys()), $sections);
        $this->assertSame(['section_blog', 'section_programs', 'section_hero'], $sections->take(3)->pluck('key')->all());
        $this->assertFalse($sections[0]['visible']);
        $this->assertTrue($sections[1]['visible']);
    }

    public function test_it_persists_section_content_and_seo_settings(): void
    {
        $component = Livewire::actingAs($this->admin())->test(LandingPageSettings::class);

        $sections = $this->withSectionState($component, 'section_programs', [
            'eyebrow' => 'Program Kami',
            'title' => 'Program Andalan Pesantren',
            'subtitle' => 'Deskripsi program yang sudah diubah.',
        ]);

        $component
            ->fillForm([
                'home_meta_title' => 'Pesantren Modern Terbaik',
                'home_meta_description' => 'Deskripsi meta khusus halaman depan.',
                'sections' => $this->withState($sections, 'section_donasi', [
                    'title' => 'Mari Berdonasi',
                    'title_highlight' => 'Untuk Santri Berprestasi',
                ]),
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified('Pengaturan halaman depan disimpan');

        $this->assertSame('Pesantren Modern Terbaik', Setting::get('home_meta_title'));
        $this->assertSame('Deskripsi meta khusus halaman depan.', Setting::get('home_meta_description'));
        $this->assertSame('Program Kami', Setting::get('section_programs_eyebrow'));
        $this->assertSame('Program Andalan Pesantren', Setting::get('section_programs_title'));
        $this->assertSame('Deskripsi program yang sudah diubah.', Setting::get('section_programs_subtitle'));
        $this->assertSame('Mari Berdonasi', Setting::get('section_donasi_title'));
        $this->assertSame('Untuk Santri Berprestasi', Setting::get('section_donasi_title_highlight'));
    }

    public function test_sections_without_editable_text_store_no_text_settings(): void
    {
        Livewire::actingAs($this->admin())
            ->test(LandingPageSettings::class)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertNull(Setting::get('section_hero_title'));
        $this->assertNull(Setting::get('section_programs_title_highlight'));
        $this->assertSame('Program Unggulan', Setting::get('section_programs_title'));
    }

    public function test_dragging_a_section_persists_the_new_order(): void
    {
        $component = Livewire::actingAs($this->admin())->test(LandingPageSettings::class);

        $sections = collect($component->get('data.sections'));
        $contact = $sections->firstWhere('key', 'section_contact');
        $reordered = $sections->reject(fn (array $section): bool => $section['key'] === 'section_contact')
            ->prepend($contact)
            ->values()
            ->all();

        $component
            ->fillForm(['sections' => $reordered])
            ->call('save')
            ->assertHasNoFormErrors();

        $order = json_decode(Setting::get('section_order'), true);

        $this->assertSame('section_contact', $order[0]['key']);
        $this->assertCount(count(HomeSections::keys()), $order);
    }

    public function test_hiding_a_section_persists_it_and_marks_its_card(): void
    {
        $admin = $this->admin();
        $component = Livewire::actingAs($admin)->test(LandingPageSettings::class);

        $component
            ->fillForm(['sections' => $this->withSectionState($component, 'section_blog', ['visible' => false])])
            ->call('save')
            ->assertHasNoFormErrors();

        $order = collect(json_decode(Setting::get('section_order'), true))->keyBy('key');

        $this->assertFalse($order['section_blog']['visible']);
        $this->assertTrue($order['section_programs']['visible']);

        Livewire::actingAs($admin)
            ->test(LandingPageSettings::class)
            ->assertSee('Disembunyikan')
            ->assertSee('Saat ini 13 dari 14 seksi tampil di halaman depan.');
    }

    public function test_clearing_texts_hides_eyebrow_and_subtitle_but_keeps_the_default_title(): void
    {
        $component = Livewire::actingAs($this->admin())->test(LandingPageSettings::class);

        $component
            ->fillForm(['sections' => $this->withSectionState($component, 'section_programs', [
                'eyebrow' => '',
                'title' => '',
                'subtitle' => '',
            ])])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('', Setting::get('section_programs_eyebrow'));
        $this->assertSame('', Setting::get('section_programs_subtitle'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Program Unggulan')
            ->assertDontSee('Keunggulan Kami')
            ->assertDontSee('Berbagai program yang dirancang untuk membentuk santri berprestasi dan berakhlak mulia.');
    }

    public function test_restore_default_text_action_refills_only_editable_cards(): void
    {
        $component = Livewire::actingAs($this->admin())->test(LandingPageSettings::class);

        $component->fillForm(['sections' => $this->withSectionState($component, 'section_programs', [
            'eyebrow' => '',
            'title' => 'Judul Sementara',
        ])]);

        $programs = $this->itemKey($component, 'section_programs');

        $component
            ->callAction(TestAction::make('restoreDefaultText')->schemaComponent('sections')->arguments(['item' => $programs]))
            ->assertNotified('Teks bawaan dipasang')
            ->assertSet("data.sections.{$programs}.eyebrow", 'Keunggulan Kami')
            ->assertSet("data.sections.{$programs}.title", 'Program Unggulan')
            ->assertActionHidden(TestAction::make('restoreDefaultText')->schemaComponent('sections')->arguments([
                'item' => $this->itemKey($component, 'section_hero'),
            ]));
    }

    public function test_saved_texts_are_shown_again_and_unset_ones_fall_back_to_defaults(): void
    {
        Setting::set('section_blog_title', 'Kabar Pesantren');

        $sections = collect(Livewire::actingAs($this->admin())->test(LandingPageSettings::class)->get('data.sections'))
            ->keyBy('key');

        $this->assertSame('Kabar Pesantren', $sections['section_blog']['title']);
        $this->assertSame('Program Unggulan', $sections['section_programs']['title']);
        $this->assertNull($sections['section_hero']['title'] ?? null);
    }

    public function test_default_texts_match_the_partials(): void
    {
        $sections = collect(Livewire::actingAs($this->admin())->test(LandingPageSettings::class)->get('data.sections'))
            ->filter(fn (array $section): bool => isset($section['title']));

        $this->assertCount(9, $sections);

        foreach ($sections as $section) {
            $key = $section['key'];
            $partial = (string) file_get_contents(
                resource_path('views/'.str_replace('.', '/', (string) HomeSections::partial($key)).'.blade.php')
            );

            $this->assertStringContainsString("setting('{$key}_title') ?: '{$section['title']}'", $partial);
            $this->assertStringContainsString("setting('{$key}_eyebrow', '{$section['eyebrow']}')", $partial);
            $this->assertStringContainsString("setting('{$key}_subtitle', '{$section['subtitle']}')", $partial);

            if (isset($section['title_highlight'])) {
                $this->assertStringContainsString("setting('{$key}_title_highlight', '{$section['title_highlight']}')", $partial);
            }
        }
    }

    public function test_cards_link_to_the_menu_that_manages_their_content(): void
    {
        Livewire::actingAs($this->admin())
            ->test(LandingPageSettings::class)
            ->assertSee('Hero Slider')
            ->assertSee(SlideResource::getUrl('index'), false);

        $settingsOnly = User::factory()->create();
        $settingsOnly->givePermissionTo('View:LandingPageSettings');

        Livewire::actingAs($settingsOnly)
            ->test(LandingPageSettings::class)
            ->assertSee('Hero Slider')
            ->assertDontSee(SlideResource::getUrl('index'), false);
    }

    public function test_a_section_whose_feature_is_off_is_flagged(): void
    {
        Setting::set('feature_toko', false);

        Livewire::actingAs($this->admin())
            ->test(LandingPageSettings::class)
            ->assertSee('Fitur Toko Buku nonaktif')
            ->assertSee('sedang dimatikan di menu')
            ->assertSee('Saat ini 13 dari 14 seksi tampil di halaman depan.');
    }

    public function test_seo_fields_count_characters(): void
    {
        Livewire::actingAs($this->admin())
            ->test(LandingPageSettings::class)
            ->assertSee('0 / 70 karakter')
            ->fillForm(['home_meta_title' => str_repeat('a', 55)])
            ->assertSee('55 / 70 karakter');
    }

    /**
     * Isi repeater dengan perubahan pada satu seksi, siap dikirim ke fillForm.
     *
     * @param  array<string, mixed>  $changes
     * @return array<string, array<string, mixed>>
     */
    private function withSectionState(Testable $component, string $key, array $changes): array
    {
        return $this->withState($component->get('data.sections'), $key, $changes);
    }

    /**
     * @param  array<string, array<string, mixed>>  $sections
     * @param  array<string, mixed>  $changes
     * @return array<string, array<string, mixed>>
     */
    private function withState(array $sections, string $key, array $changes): array
    {
        return collect($sections)
            ->map(fn (array $section): array => $section['key'] === $key ? [...$section, ...$changes] : $section)
            ->all();
    }

    private function itemKey(Testable $component, string $key): string
    {
        return (string) collect($component->get('data.sections'))
            ->search(fn (array $section): bool => $section['key'] === $key);
    }
}
