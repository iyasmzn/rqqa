{{--
    Token bentuk halaman publik: skala radius dan bentuk pita kepala halaman
    (`.page-band`). Di-include welcome.blade.php dan layouts/public.blade.php
    agar halaman depan dan subhalaman memakai bentuk yang sama.
--}}
<style>
    :root {
        /* Jarak antara tepi layar dan blok hero yang membulat */
        --shell-gap: .875rem;

        /* Skala radius — kartu & media membulat besar, kontrol tetap tegas */
        --radius-card:  2.5rem;   /* 40px */
        --radius-media: 2rem;     /* 32px */
        --radius-sm:    1rem;
        --radius-btn:   .75rem;   /* 12px */
        --radius-pill:  9999px;
    }

    /* Pita kepala halaman — hero halaman depan maupun header subhalaman —
       tampil sebagai blok membulat yang menjorok dari tepi layar, bukan pita
       selebar layar. Warna, pola, dan tingginya tetap milik tiap halaman;
       yang dibagikan di sini hanya bentuknya. */
    .page-band {
        position: relative;
        overflow: hidden;
        border-radius: 0 0 var(--radius-card) var(--radius-card);
    }
    @media (min-width: 768px) {
        .page-band {
            margin-inline: var(--shell-gap);
            border-radius: var(--radius-card);
        }

        /* Pita juga menjorok dari atas. Tambahan 4px menutup selisih tarikan
           hero (`-mt-17` = 68px) dengan tinggi navbar (`h-16` = 64px), sehingga
           jarak atasnya sama persis dengan `--shell-gap` di kiri dan kanan. */
        body.page-inset { padding-top: calc(var(--shell-gap) + 4px); }
    }
</style>
