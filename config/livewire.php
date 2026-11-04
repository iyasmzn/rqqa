<?php

/*
 * Hanya kunci yang diubah dari bawaan Livewire; kunci lain tetap diambil dari
 * vendor/livewire/livewire/config/livewire.php. Penggabungannya dangkal (per
 * kunci teratas), jadi blok `temporary_file_upload` ditulis utuh.
 */
return [

    'temporary_file_upload' => [
        'disk' => env('LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK'),
        // Bawaan Livewire 12 MB — terlalu kecil untuk PDF e-book (maks. 50 MB).
        // Batas efektif tiap field tetap maxSize()-nya dan batas unggah PHP.
        'rules' => ['required', 'file', 'max:51200'],
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        // Unggahan PDF besar di koneksi lambat bisa melewati 5 menit bawaan.
        'max_upload_time' => 15,
        'cleanup' => true,
    ],

];
