@extends('layouts.public')

@section('content')

<section class="min-h-screen flex items-center justify-center py-20 px-4"
         style="background:linear-gradient(135deg,#082828 0%,#08484A 60%,#0a6060 100%)">

    <div class="w-full max-w-md" data-aos="fade-up">

        {{-- Logo --}}
        <div class="text-center mb-8">
            @if(setting('site_logo'))
                <img src="{{ asset('storage/'.setting('site_logo')) }}"
                     alt="{{ setting('site_name') }}"
                     class="w-16 h-16 rounded-2xl object-contain mx-auto mb-4">
            @else
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center mx-auto mb-4">
                    <span class="text-white font-extrabold text-2xl">{{ strtoupper(substr(setting('site_name', 'Q'), 0, 1)) }}</span>
                </div>
            @endif
            <h1 class="text-2xl font-extrabold text-white">Buat Akun</h1>
            <p class="text-sm mt-1" style="color:rgba(255,255,255,.65)">
                Daftar untuk mulai berbelanja buku
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8">

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="mb-5 px-4 py-3 rounded-xl text-sm font-medium text-red-700 bg-red-50 border border-red-200">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text)">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Ahmad Fauzi"
                           class="w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all focus:ring-2"
                           style="border-color:var(--border);background:var(--bg);color:var(--text)">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text)">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="nama@email.com"
                           class="w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all focus:ring-2"
                           style="border-color:var(--border);background:var(--bg);color:var(--text)">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text)">Kata Sandi</label>
                    <input type="password" name="password" required
                           placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all focus:ring-2"
                           style="border-color:var(--border);background:var(--bg);color:var(--text)">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1.5" style="color:var(--text)">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" required
                           placeholder="Ulangi kata sandi"
                           class="w-full px-4 py-3 rounded-xl border text-sm outline-none transition-all focus:ring-2"
                           style="border-color:var(--border);background:var(--bg);color:var(--text)">
                </div>

                <button type="submit" class="btn-primary w-full justify-center py-3">
                    Daftar Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            <p class="text-center text-sm mt-6" style="color:var(--muted)">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold hover:opacity-75 transition-opacity"
                   style="color:var(--primary)">Masuk di sini</a>
            </p>

            <p class="text-center text-sm mt-2" style="color:var(--muted)">
                <a href="{{ route('home') }}" class="hover:opacity-75 transition-opacity">← Kembali ke Beranda</a>
            </p>
        </div>
    </div>
</section>

@endsection
