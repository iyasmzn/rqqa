<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $album = $request->query('album');

        $query = Media::where('show_in_gallery', true)
            ->where('mime_type', 'like', 'image/%')
            ->orderByDesc('created_at');

        if ($album) {
            $query->where('album', $album);
        }

        $images = $query->paginate(24)->withQueryString();

        $albums = Media::where('show_in_gallery', true)
            ->where('mime_type', 'like', 'image/%')
            ->whereNotNull('album')
            ->distinct()
            ->orderBy('album')
            ->pluck('album');

        $appName = config('app.name');

        $seo = [
            'title' => "Galeri Foto | {$appName}",
            'description' => "Galeri foto dan dokumentasi kegiatan {$appName}. Fasilitas, wisuda, dan berbagai momen berharga pesantren.",
            'canonical' => route('gallery.index'),
        ];

        return view('gallery.index', compact('images', 'albums', 'album', 'seo'));
    }
}
