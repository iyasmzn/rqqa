<?php

namespace Tests\Feature;

use App\Models\Ebook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EbookModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: ?string}>
     */
    public static function embedInputs(): array
    {
        return [
            'url biasa' => [
                'https://heyzine.com/flip-book/abc.html',
                'https://heyzine.com/flip-book/abc.html',
            ],
            'kode iframe' => [
                '<iframe src="https://online.fliphtml5.com/abc/def/" width="100%" height="600" allowfullscreen></iframe>',
                'https://online.fliphtml5.com/abc/def/',
            ],
            'iframe kutip tunggal & entitas' => [
                "<iframe allowfullscreen src='https://example.com/view?a=1&amp;b=2'></iframe>",
                'https://example.com/view?a=1&b=2',
            ],
            'tanpa skema' => [
                '//www.example.com/embed/1',
                'https://www.example.com/embed/1',
            ],
            'tautan berbagi google drive' => [
                'https://drive.google.com/file/d/1AbC_dEf-123/view?usp=sharing',
                'https://drive.google.com/file/d/1AbC_dEf-123/preview',
            ],
            'google drive open dengan resourcekey' => [
                'https://drive.google.com/open?id=1AbC&resourcekey=0-xyz',
                'https://drive.google.com/file/d/1AbC/preview?resourcekey=0-xyz',
            ],
            'google docs edit' => [
                'https://docs.google.com/document/d/1DoC/edit?usp=sharing',
                'https://docs.google.com/document/d/1DoC/preview',
            ],
            'google docs publish tetap' => [
                'https://docs.google.com/document/d/e/2PACX-1vR/pub?embedded=true',
                'https://docs.google.com/document/d/e/2PACX-1vR/pub?embedded=true',
            ],
            'skema javascript' => ['javascript:alert(1)', null],
            'iframe berskema javascript' => ['<iframe src="javascript:alert(1)"></iframe>', null],
            'bukan url' => ['buku saya', null],
        ];
    }

    #[DataProvider('embedInputs')]
    public function test_it_normalizes_embed_input(string $input, ?string $expected): void
    {
        $this->assertSame($expected, Ebook::normalizeEmbedUrl($input));
    }

    public function test_embed_url_is_normalized_when_saved(): void
    {
        $ebook = Ebook::factory()
            ->embed('<iframe src="https://drive.google.com/file/d/XYZ/view"></iframe>')
            ->create();

        $this->assertSame('https://drive.google.com/file/d/XYZ/preview', $ebook->fresh()->embed_url);
    }

    public function test_featured_or_latest_prefers_featured_ebooks_in_their_order(): void
    {
        $second = Ebook::factory()->featured()->create(['sort_order' => 2]);
        $first = Ebook::factory()->featured()->create(['sort_order' => 1]);
        Ebook::factory()->create();
        Ebook::factory()->featured()->unpublished()->create();

        $this->assertSame([$first->id, $second->id], Ebook::featuredOrLatest(8)->modelKeys());
    }

    public function test_featured_or_latest_falls_back_to_the_newest_published_ebooks(): void
    {
        $older = Ebook::factory()->create(['created_at' => now()->subDay()]);
        $newer = Ebook::factory()->create();
        Ebook::factory()->unpublished()->create();

        $this->assertSame([$newer->id, $older->id], Ebook::featuredOrLatest(8)->modelKeys());
        $this->assertCount(1, Ebook::featuredOrLatest(1));
    }

    public function test_replacing_or_deleting_the_pdf_removes_the_old_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('ebooks/files/lama.pdf', '%PDF');
        Storage::disk('public')->put('ebooks/files/baru.pdf', '%PDF');

        $ebook = Ebook::factory()->create(['file_path' => 'ebooks/files/lama.pdf']);

        $ebook->update(['file_path' => 'ebooks/files/baru.pdf']);

        Storage::disk('public')->assertMissing('ebooks/files/lama.pdf');
        Storage::disk('public')->assertExists('ebooks/files/baru.pdf');

        $ebook->delete();

        Storage::disk('public')->assertMissing('ebooks/files/baru.pdf');
    }

    public function test_updating_other_fields_keeps_the_pdf(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('ebooks/files/tetap.pdf', '%PDF');

        $ebook = Ebook::factory()->create(['file_path' => 'ebooks/files/tetap.pdf']);
        $ebook->update(['title' => 'Judul Baru', 'is_featured' => true]);

        Storage::disk('public')->assertExists('ebooks/files/tetap.pdf');
    }

    public function test_it_never_deletes_files_outside_the_ebook_folder(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('teachers/foto.jpg', 'jpg');
        Storage::disk('public')->put('ebooks/covers/sampul.jpg', 'jpg');

        Ebook::factory()->create(['file_path' => 'teachers/foto.jpg'])->delete();
        Ebook::factory()->create(['file_path' => 'ebooks/files/../covers/sampul.jpg'])->delete();

        Storage::disk('public')->assertExists('teachers/foto.jpg');
        Storage::disk('public')->assertExists('ebooks/covers/sampul.jpg');
    }

    public function test_labels_and_capabilities_follow_the_source_type(): void
    {
        $pdf = Ebook::factory()->pdf()->make(['file_size' => 524_288]);
        $locked = Ebook::factory()->pdf()->make(['allow_download' => false]);
        $link = Ebook::factory()->link()->make();
        $embed = Ebook::factory()->embed()->make();

        $this->assertSame('PDF', $pdf->format_label);
        $this->assertSame('512 KB', $pdf->file_size_label);
        $this->assertTrue($pdf->hasReader());
        $this->assertTrue($pdf->canBeDownloaded());
        $this->assertFalse($locked->canBeDownloaded());

        $this->assertSame('Tautan Eksternal', $link->format_label);
        $this->assertFalse($link->hasReader());
        $this->assertFalse($link->canBeDownloaded());
        $this->assertSame('perpustakaan.example.com', $link->external_host);

        $this->assertSame('Baca Online', $embed->format_label);
        $this->assertTrue($embed->hasReader());
        $this->assertSame($embed->embed_url, $embed->read_url);
    }
}
