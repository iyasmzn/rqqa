<?php

namespace Database\Seeders;

use App\Models\Ebook;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookSeeder extends Seeder
{
    public function run(): void
    {
        if (Ebook::exists()) {
            return;
        }

        $ebooks = [
            [
                'title' => 'Panduan Belajar Mandiri',
                'author' => 'Tim Kurikulum',
                'publisher' => 'Penerbit Sekolah',
                'published_year' => 2025,
                'language' => 'Indonesia',
                'category' => 'Modul',
                'description' => "Panduan praktis menyusun jadwal, mencatat, dan mengulang pelajaran secara mandiri di rumah.\n\nIni contoh e-book PDF — ganti atau hapus dari panel admin.",
                'source_type' => Ebook::SOURCE_PDF,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Modul Literasi Digital',
                'author' => 'Tim Literasi',
                'publisher' => 'Penerbit Sekolah',
                'published_year' => 2024,
                'language' => 'Indonesia',
                'category' => 'Buku Pelajaran',
                'description' => 'Mengenal etika bermedia sosial, keamanan akun, dan cara memeriksa kebenaran informasi di internet.',
                'source_type' => Ebook::SOURCE_PDF,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Adab Penuntut Ilmu',
                'author' => 'Dewan Asatidz',
                'publisher' => 'Penerbit Sekolah',
                'published_year' => 2023,
                'language' => 'Indonesia',
                'category' => 'Kitab',
                'description' => 'Ringkasan adab-adab penuntut ilmu terhadap guru, kitab, dan majelis ilmu.',
                'source_type' => Ebook::SOURCE_PDF,
                'allow_download' => false,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => "Alice's Adventures in Wonderland",
                'author' => 'Lewis Carroll',
                'publisher' => 'Project Gutenberg',
                'published_year' => 2008,
                'language' => 'Inggris',
                'category' => 'Bacaan Umum',
                'description' => 'Novel klasik berdomain publik untuk latihan membaca bahasa Inggris, tersedia gratis di Project Gutenberg.',
                'source_type' => Ebook::SOURCE_LINK,
                'external_url' => 'https://www.gutenberg.org/ebooks/11',
                'is_featured' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($ebooks as $data) {
            $data['slug'] = Str::slug($data['title']);

            if ($data['source_type'] === Ebook::SOURCE_PDF) {
                $pdf = $this->samplePdf($data['title'], 'Contoh e-book - ganti atau hapus dari panel admin.');
                $data['file_path'] = Ebook::FILE_DIRECTORY."/{$data['slug']}.pdf";
                $data['original_filename'] = "{$data['slug']}.pdf";
                $data['file_size'] = strlen($pdf);

                Storage::disk('public')->put($data['file_path'], $pdf);
            }

            Ebook::create($data);
        }
    }

    /**
     * A one-page PDF showing the given title, so a fresh install can show the
     * PDF reader without shipping sample files. Text must be plain ASCII.
     */
    private function samplePdf(string $title, string $subtitle): string
    {
        $escape = fn (string $text): string => strtr($text, ['\\' => '\\\\', '(' => '\\(', ')' => '\\)']);
        $titleText = $escape($title);
        $subtitleText = $escape($subtitle);

        $content = "BT /F1 26 Tf 72 740 Td ({$titleText}) Tj ET\nBT /F1 13 Tf 72 710 Td ({$subtitleText}) Tj ET";

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>',
            '<< /Length '.strlen($content)." >>\nstream\n{$content}\nendstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n{$object}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";

        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        return $pdf."trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF\n";
    }
}
