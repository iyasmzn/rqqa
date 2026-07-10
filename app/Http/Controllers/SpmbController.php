<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AdmissionPath;
use App\Models\RegistrationWave;
use App\Models\Setting;
use App\Models\SpmbRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SpmbController extends Controller
{
    public function index(): View
    {
        $procedures = json_decode(Setting::get('spmb_procedures', ''), true) ?: $this->defaultProcedures();
        $fees = json_decode(Setting::get('spmb_fees', ''), true) ?: [];
        $paths = AdmissionPath::query()->active()->ordered()->get();
        $activeYear = AcademicYear::active();
        $waves = $activeYear
            ? $activeYear->waves()->where('is_active', true)->orderBy('start_date')->get()
            : collect();
        $siteName = setting('site_name', config('app.name'));
        $yearLabel = spmb_year_label();

        $seo = [
            'title' => "PPDB / SPMB {$yearLabel} | {$siteName}",
            'description' => "Informasi Penerimaan Peserta Didik Baru (PPDB) {$siteName}. Prosedur pendaftaran, biaya, dan formulir online SPMB.",
            'canonical' => route('ppdb.index'),
        ];

        return view('ppdb.index', compact('procedures', 'fees', 'paths', 'waves', 'seo'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (! (bool) Setting::get('spmb_form_enabled', true)) {
            return back()->with('error', Setting::get('spmb_closed_message', 'Form pendaftaran saat ini sedang ditutup.'));
        }

        $wave = RegistrationWave::currentOpen();

        if ($wave === null) {
            return back()->with('error', 'SPMB saat ini tidak dalam masa penerimaan.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'digits:16', Rule::unique('spmb_registrations', 'nik')],
            'email' => ['nullable', 'email', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'previous_school' => ['required', 'string', 'max:100'],
            'previous_school_city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'admission_path_id' => ['required', Rule::exists('admission_paths', 'id')->where('is_active', true)],
            'parent_name' => ['nullable', 'string', 'max:100'],
            'parent_phone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar. Setiap calon peserta hanya dapat mendaftar satu kali.',
        ]);

        $validated['academic_year_id'] = $wave->academic_year_id;
        $validated['registration_wave_id'] = $wave->id;

        SpmbRegistration::create($validated);

        return redirect()->route('ppdb.index')
            ->with('success', 'Pendaftaran berhasil dikirim! Kami akan segera menghubungi Anda untuk proses verifikasi.');
    }

    /** @return array<int, array<string, mixed>> */
    private function defaultProcedures(): array
    {
        return [
            ['icon' => '📝', 'title' => 'Isi Formulir Online', 'description' => 'Kunjungi halaman PPDB dan isi formulir pendaftaran secara lengkap dan benar.'],
            ['icon' => '📁', 'title' => 'Siapkan Berkas', 'description' => 'Persiapkan dokumen yang diperlukan: ijazah/SHUN, rapor, dan pas foto terbaru.'],
            ['icon' => '✅', 'title' => 'Verifikasi Berkas', 'description' => 'Datang ke sekolah untuk verifikasi berkas pada tanggal yang telah ditentukan.'],
            ['icon' => '🎉', 'title' => 'Pengumuman Hasil', 'description' => 'Hasil seleksi diumumkan melalui halaman resmi sekolah dan via WhatsApp/email.'],
        ];
    }
}
