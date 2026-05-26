<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookController extends Controller
{
    private const PER_PAGE = 12;

    public function index(Request $request): View
    {
        $category = $request->query('category');

        $books = Book::available()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->ordered()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $categories = Book::available()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter();

        $appName = config('app.name');

        $seo = [
            'title' => "Toko Buku | {$appName}",
            'description' => "Beli buku pilihan dari {$appName}. Koleksi kitab, buku agama, dan referensi pendidikan berkualitas.",
            'canonical' => route('books.index'),
        ];

        return view('books.index', compact('books', 'categories', 'category', 'seo'));
    }

    public function show(Book $book): View
    {
        abort_unless($book->is_available, 404);

        $related = Book::available()
            ->where('id', '!=', $book->id)
            ->where('category', $book->category)
            ->ordered()
            ->limit(4)
            ->get();

        $seo = [
            'title' => "{$book->title} | ".config('app.name'),
            'description' => $book->description ? Str::limit(strip_tags($book->description), 155) : "Beli buku {$book->title} di toko buku ".config('app.name'),
            'canonical' => route('books.show', $book),
            'og_image' => $book->cover_url,
        ];

        return view('books.show', compact('book', 'related', 'seo'));
    }
}
