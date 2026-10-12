<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
    ],

    // --- Integrasi webhook Layanan Madrasah ---
    'layanan_madrasah' => [
        'webhook_secret' => env('LAYANAN_MADRASAH_WEBHOOK_SECRET'),

        // Dipakai HANYA kalau tipe_layanan yang masuk tidak dikenali sama
        // sekali oleh layanan_id_map di bawah (seharusnya tidak pernah
        // terjadi selama WebhookController::TIPE_LAYANAN_VALID tetap berisi
        // tipe yang sama dengan key layanan_id_map). Default tetap 26
        // ("Surat Rekomendasi") sebagai fallback paling aman.
        'default_layanan_id' => (int) env('LAYANAN_MADRASAH_DEFAULT_LAYANAN_ID', 26),

        // Mapping tipe_layanan (dari Layanan Madrasah) -> id tabel `layanan`
        // milik PTSP, supaya tiap jenis ajuan tercatat sebagai layanan yang
        // benar/berbeda (bukan semuanya numpuk ke satu id 26) untuk
        // kebutuhan laporan/statistik per-layanan di PTSP.
        //
        // Sesuaikan lewat .env kalau ID layanan di production berbeda
        // dari default di bawah ini (kosongkan/0 untuk fallback ke
        // default_layanan_id).
        'layanan_id_map' => [
            'rekomendasi'       => (int) env('LAYANAN_MADRASAH_LAYANAN_ID_REKOMENDASI', 62),
            'rekomendasi_kamad' => (int) env('LAYANAN_MADRASAH_LAYANAN_ID_REKOMENDASI_KAMAD', 61),
            'pindah_sekolah'    => (int) env('LAYANAN_MADRASAH_LAYANAN_ID_PINDAH_SEKOLAH', 26),
            'input_siswa'       => (int) env('LAYANAN_MADRASAH_LAYANAN_ID_INPUT_SISWA', 67),
            'reaktivasi'        => (int) env('LAYANAN_MADRASAH_LAYANAN_ID_REAKTIVASI', 67),
            'reset_verval'      => (int) env('LAYANAN_MADRASAH_LAYANAN_ID_RESET_VERVAL', 69),
            'salah_tingkat'     => (int) env('LAYANAN_MADRASAH_LAYANAN_ID_SALAH_TINGKAT', 67),
            'perubahan_ijop'    => (int) env('LAYANAN_MADRASAH_LAYANAN_ID_PERUBAHAN_IJOP', 57),
        ],

        // Opsional: daftar IP/CIDR yang boleh memanggil endpoint webhook,
        // sebagai lapisan tambahan di luar secret + HMAC signature. Isi
        // comma-separated di .env, contoh:
        //   LAYANAN_MADRASAH_WEBHOOK_IP_ALLOWLIST=203.0.113.10,203.0.113.0/24
        // Kosongkan (default) untuk MENONAKTIFKAN pengecekan ini.
        // Lihat VerifyLayananMadrasahWebhookSecret.
        'webhook_ip_allowlist' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('LAYANAN_MADRASAH_WEBHOOK_IP_ALLOWLIST', ''))
        ))),
    ],

];
