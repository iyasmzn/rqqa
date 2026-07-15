<?php

namespace App\Http\Controllers;

use App\Http\Concerns\ProtectsAgainstSpam;
use App\Models\Post;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class QuestionController extends Controller
{
    use ProtectsAgainstSpam;

    public function index(): View
    {
        $questions = Question::published()->answered()->general()
            ->orderByDesc('answered_at')
            ->paginate(10);

        $appName = config('app.name');

        $seo = [
            'title' => "Tanya Jawab | {$appName}",
            'description' => "Kirim pertanyaan seputar pesantren dan baca jawaban dari ustaz/ustazah {$appName}.",
            'canonical' => route('questions.index'),
        ];

        return view('questions.index', compact('questions', 'seo'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate($this->spamProtectionRules($request));

        $validated = $request->validate([
            'post_id' => ['nullable', 'integer', 'exists:posts,id'],
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:200'],
            'question' => ['required', 'string', 'max:2000'],
        ]);

        if ($validated['post_id'] ?? null) {
            $post = Post::findOrFail($validated['post_id']);

            if (! $post->allow_questions) {
                throw ValidationException::withMessages([
                    'question' => 'Pertanyaan tidak diizinkan pada artikel ini.',
                ]);
            }
        }

        Question::create($validated);

        return back()->with('success', 'Pertanyaan Anda telah terkirim. Kami akan segera menjawabnya.');
    }
}
