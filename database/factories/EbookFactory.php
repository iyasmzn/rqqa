<?php

namespace Database\Factories;

use App\Models\Ebook;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ebook>
 */
class EbookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->randomElement([
            'Panduan Belajar Mandiri', 'Modul Literasi Digital', 'Kumpulan Cerita Inspiratif',
            'Dasar-Dasar Fiqih Ibadah', 'Mengenal Sejarah Islam', 'Tuntunan Shalat Lengkap',
            'Pengantar Ilmu Tajwid', 'Adab Penuntut Ilmu', 'Matematika Kelas 7',
            'Bahasa Arab untuk Pemula',
        ]).' '.Str::random(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(4),
            'author' => $this->faker->name(),
            'publisher' => $this->faker->company(),
            'published_year' => $this->faker->numberBetween(2010, 2025),
            'isbn' => $this->faker->isbn13(),
            'language' => $this->faker->randomElement(['Indonesia', 'Arab', 'Inggris']),
            'pages' => $this->faker->numberBetween(40, 400),
            'category' => $this->faker->randomElement(['Buku Pelajaran', 'Modul', 'Kitab', 'Bacaan Umum']),
            'description' => $this->faker->paragraph(3),
            'cover_image' => null,
            'source_type' => Ebook::SOURCE_PDF,
            'file_path' => Ebook::FILE_DIRECTORY.'/'.Str::random(12).'.pdf',
            'original_filename' => Str::slug($title).'.pdf',
            'file_size' => $this->faker->numberBetween(200_000, 20_000_000),
            'external_url' => null,
            'embed_url' => null,
            'allow_download' => true,
            'is_featured' => false,
            'is_published' => true,
            'sort_order' => 0,
        ];
    }

    public function pdf(): static
    {
        return $this->state(fn (array $attributes): array => [
            'source_type' => Ebook::SOURCE_PDF,
            'file_path' => Ebook::FILE_DIRECTORY.'/'.Str::random(12).'.pdf',
            'original_filename' => Str::slug($attributes['title']).'.pdf',
            'external_url' => null,
            'embed_url' => null,
        ]);
    }

    public function link(?string $url = null): static
    {
        return $this->state(fn (): array => [
            'source_type' => Ebook::SOURCE_LINK,
            'file_path' => null,
            'original_filename' => null,
            'file_size' => null,
            'external_url' => $url ?? 'https://perpustakaan.example.com/buku/'.Str::random(8),
            'embed_url' => null,
        ]);
    }

    public function embed(?string $url = null): static
    {
        return $this->state(fn (): array => [
            'source_type' => Ebook::SOURCE_EMBED,
            'file_path' => null,
            'original_filename' => null,
            'file_size' => null,
            'external_url' => null,
            'embed_url' => $url ?? 'https://flipbook.example.com/read/'.Str::random(8),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (): array => ['is_featured' => true]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (): array => ['is_published' => false]);
    }
}
