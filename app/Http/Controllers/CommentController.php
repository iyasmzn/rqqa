<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        if (! $post->allow_comments) {
            throw ValidationException::withMessages([
                'body' => 'Komentar tidak diizinkan pada artikel ini.',
            ]);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return back()
            ->with('comment_success', 'Komentar Anda telah dikirim.')
            ->withFragment('komentar');
    }
}
