<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Ebooks\Pages\CreateEbook;
use App\Filament\Resources\Ebooks\Pages\EditEbook;
use App\Filament\Resources\Ebooks\Pages\ListEbooks;
use App\Models\Category;
use App\Models\Ebook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class EbookResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->actingAs($this->userWith(['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny']));
    }

    /**
     * A user holding the given Shield permissions on the Ebook resource.
     *
     * @param  list<string>  $actions
     */
    private function userWith(array $actions): User
    {
        $user = User::factory()->create();

        $user->givePermissionTo(array_map(
            fn (string $action): Permission => Permission::findOrCreate("{$action}:Ebook", 'web'),
            $actions,
        ));

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $user;
    }

    public function test_it_lists_ebooks(): void
    {
        $ebooks = Ebook::factory()->count(3)->create();

        Livewire::test(ListEbooks::class)
            ->assertCanSeeTableRecords($ebooks);
    }

    public function test_default_ebook_categories_are_installed_by_migration(): void
    {
        $this->assertSame(
            ['Buku Pelajaran', 'Modul', 'Kitab', 'Referensi', 'Bacaan Umum', 'Lainnya'],
            array_values(Category::optionsForType(Category::TYPE_EBOOK)),
        );
    }

    public function test_it_creates_a_pdf_ebook_from_an_upload(): void
    {
        Category::create(['type' => Category::TYPE_EBOOK, 'name' => 'Modul Ajar', 'sort_order' => 9, 'is_active' => true]);

        Livewire::test(CreateEbook::class)
            ->fillForm([
                'title' => 'Modul Literasi Digital',
                'slug' => 'modul-literasi-digital',
                'category' => 'Modul Ajar',
                'source_type' => Ebook::SOURCE_PDF,
                'file_path' => UploadedFile::fake()->create('Modul Literasi.pdf', 300, 'application/pdf'),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $ebook = Ebook::sole();

        $this->assertSame('modul-literasi-digital', $ebook->slug);
        $this->assertSame('Modul Ajar', $ebook->category);
        $this->assertSame('Modul Literasi.pdf', $ebook->original_filename);
        $this->assertStringStartsWith('ebooks/files/', $ebook->file_path);
        Storage::disk('public')->assertExists($ebook->file_path);
        $this->assertSame(Storage::disk('public')->size($ebook->file_path), $ebook->file_size);
        $this->assertTrue($ebook->allow_download);
        $this->assertTrue($ebook->is_published);
        $this->assertFalse($ebook->is_featured);
    }

    public function test_it_rejects_a_non_pdf_upload(): void
    {
        Livewire::test(CreateEbook::class)
            ->fillForm([
                'title' => 'Bukan PDF',
                'slug' => 'bukan-pdf',
                'source_type' => Ebook::SOURCE_PDF,
                'file_path' => UploadedFile::fake()->create('skrip.php', 1, 'application/x-php'),
            ])
            ->call('create')
            ->assertHasFormErrors(['file_path']);

        $this->assertSame(0, Ebook::count());
    }

    public function test_it_creates_a_link_ebook(): void
    {
        Livewire::test(CreateEbook::class)
            ->fillForm([
                'title' => 'Alice in Wonderland',
                'slug' => 'alice-in-wonderland',
                'source_type' => Ebook::SOURCE_LINK,
                'external_url' => 'https://www.gutenberg.org/ebooks/11',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Ebook::class, [
            'slug' => 'alice-in-wonderland',
            'source_type' => Ebook::SOURCE_LINK,
            'external_url' => 'https://www.gutenberg.org/ebooks/11',
            'file_path' => null,
            'embed_url' => null,
        ]);
    }

    public function test_it_creates_an_embed_ebook_from_an_iframe_snippet(): void
    {
        Livewire::test(CreateEbook::class)
            ->fillForm([
                'title' => 'Flipbook Profil',
                'slug' => 'flipbook-profil',
                'source_type' => Ebook::SOURCE_EMBED,
                'embed_url' => '<iframe src="https://drive.google.com/file/d/1AbC/view?usp=sharing" width="640"></iframe>',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame('https://drive.google.com/file/d/1AbC/preview', Ebook::sole()->embed_url);
    }

    public function test_each_source_type_requires_its_own_field(): void
    {
        Livewire::test(CreateEbook::class)
            ->fillForm(['title' => 'Tanpa Isi', 'slug' => 'tanpa-isi', 'source_type' => Ebook::SOURCE_PDF])
            ->call('create')
            ->assertHasFormErrors(['file_path' => 'required']);

        Livewire::test(CreateEbook::class)
            ->fillForm(['title' => 'Tanpa Isi', 'slug' => 'tanpa-isi', 'source_type' => Ebook::SOURCE_LINK])
            ->call('create')
            ->assertHasFormErrors(['external_url' => 'required']);

        Livewire::test(CreateEbook::class)
            ->fillForm(['title' => 'Tanpa Isi', 'slug' => 'tanpa-isi', 'source_type' => Ebook::SOURCE_EMBED])
            ->call('create')
            ->assertHasFormErrors(['embed_url' => 'required']);
    }

    public function test_it_rejects_unsafe_or_invalid_urls(): void
    {
        Livewire::test(CreateEbook::class)
            ->fillForm([
                'title' => 'Tautan Berbahaya',
                'slug' => 'tautan-berbahaya',
                'source_type' => Ebook::SOURCE_LINK,
                'external_url' => 'ftp://files.example.com/buku.pdf',
            ])
            ->call('create')
            ->assertHasFormErrors(['external_url']);

        Livewire::test(CreateEbook::class)
            ->fillForm([
                'title' => 'Sematan Rusak',
                'slug' => 'sematan-rusak',
                'source_type' => Ebook::SOURCE_EMBED,
                'embed_url' => '<iframe src="javascript:alert(1)"></iframe>',
            ])
            ->call('create')
            ->assertHasFormErrors(['embed_url']);

        $this->assertSame(0, Ebook::count());
    }

    public function test_switching_a_pdf_ebook_to_a_link_clears_and_deletes_the_pdf(): void
    {
        Storage::disk('public')->put('ebooks/files/lama.pdf', '%PDF');

        $ebook = Ebook::factory()->pdf()->create([
            'file_path' => 'ebooks/files/lama.pdf',
            'original_filename' => 'lama.pdf',
            'file_size' => 4,
        ]);

        Livewire::test(EditEbook::class, ['record' => $ebook->getRouteKey()])
            ->fillForm([
                'source_type' => Ebook::SOURCE_LINK,
                'external_url' => 'https://perpustakaan.example.com/buku/1',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $ebook->refresh();

        $this->assertSame(Ebook::SOURCE_LINK, $ebook->source_type);
        $this->assertSame('https://perpustakaan.example.com/buku/1', $ebook->external_url);
        $this->assertNull($ebook->file_path);
        $this->assertNull($ebook->original_filename);
        $this->assertNull($ebook->file_size);
        Storage::disk('public')->assertMissing('ebooks/files/lama.pdf');
    }

    public function test_editing_a_pdf_ebook_keeps_its_file(): void
    {
        Storage::disk('public')->put('ebooks/files/tetap.pdf', '%PDF-1.4');

        $ebook = Ebook::factory()->pdf()->create(['file_path' => 'ebooks/files/tetap.pdf', 'file_size' => null]);

        Livewire::test(EditEbook::class, ['record' => $ebook->getRouteKey()])
            ->fillForm(['title' => 'Judul Diperbarui'])
            ->call('save')
            ->assertHasNoFormErrors();

        $ebook->refresh();

        $this->assertSame('Judul Diperbarui', $ebook->title);
        $this->assertSame('ebooks/files/tetap.pdf', $ebook->file_path);
        $this->assertSame(8, $ebook->file_size);
        Storage::disk('public')->assertExists('ebooks/files/tetap.pdf');
    }

    public function test_it_rejects_a_tampered_file_path(): void
    {
        Storage::disk('public')->put('teachers/foto.jpg', 'jpg');

        $ebook = Ebook::factory()->pdf()->create();
        $original = $ebook->file_path;

        Livewire::test(EditEbook::class, ['record' => $ebook->getRouteKey()])
            // Bentuk state FileUpload: [uuid => path] — path-nya diganti di sisi klien.
            ->fillForm(['file_path' => ['b8f4c2d0-tampered' => 'teachers/foto.jpg']])
            ->call('save')
            ->assertHasFormErrors(['file_path']);

        $this->assertSame($original, $ebook->fresh()->file_path);
        Storage::disk('public')->assertExists('teachers/foto.jpg');
    }

    public function test_editor_can_toggle_featured_from_the_table(): void
    {
        $ebook = Ebook::factory()->create();

        Livewire::test(ListEbooks::class)
            ->call('updateTableColumnState', 'is_featured', (string) $ebook->getKey(), true);

        $this->assertTrue($ebook->fresh()->is_featured);
    }

    public function test_featured_toggle_is_locked_without_update_permission(): void
    {
        $ebook = Ebook::factory()->create();

        $this->actingAs($this->userWith(['ViewAny', 'View']));

        Livewire::test(ListEbooks::class)
            ->call('updateTableColumnState', 'is_featured', (string) $ebook->getKey(), true);

        $this->assertFalse($ebook->fresh()->is_featured);
    }
}
