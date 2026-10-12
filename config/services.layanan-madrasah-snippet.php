<?php

/**
 * TEMPEL blok array ini ke dalam config/services.php (PTSP Online),
 * di antara key-key lain yang sudah ada.
 */

return [
    'layanan_madrasah' => [
        // Token rahasia yang HARUS sama persis dengan PTSP_WEBHOOK_SECRET
        // di aplikasi Layanan Madrasah. Divalidasi dari header X-Webhook-Secret.
        'webhook_secret' => env('LAYANAN_MADRASAH_WEBHOOK_SECRET'),

        // ID pada tabel `layanan` (PTSP) yang dipakai sebagai layanan default
        // untuk permohonan yang masuk lewat webhook. Dari dump SQL yang
        // dilampirkan, id=26 = "Surat Rekomendasi" (kode 'SP') tampak paling
        // cocok — TAPI SILAKAN VERIFIKASI ULANG ke tabel `layanan` di DB
        // production sebelum go-live (lihat HANDOFF_NOTES.md).
        'default_layanan_id' => (int) env('LAYANAN_MADRASAH_DEFAULT_LAYANAN_ID', 26),
    ],
];
