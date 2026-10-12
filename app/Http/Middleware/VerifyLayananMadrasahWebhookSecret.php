<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

/**
 * Memvalidasi header X-Webhook-Secret pada setiap request masuk dari
 * aplikasi Layanan Madrasah, sebelum request diteruskan ke WebhookController.
 *
 * Menggunakan hash_equals() untuk mencegah timing attack saat membandingkan
 * string secret.
 */
class VerifyLayananMadrasahWebhookSecret
{
    public function handle(Request $request, Closure $next): Response
    {
        // Lapisan ke-0 (ditambahkan sesi 3, item 3g/HANDOFF_NOTES.md sesi 2):
        // IP allowlist opsional. Dicek PALING AWAL, sebelum secret/HMAC,
        // supaya IP yang tidak dikenal langsung ditolak tanpa perlu
        // membandingkan secret. Kalau allowlist kosong di config (default),
        // pengecekan ini dilewati sepenuhnya -- tidak mengubah perilaku lama.
        $allowlist = (array) config('services.layanan_madrasah.webhook_ip_allowlist', []);

        if (!empty($allowlist) && !IpUtils::checkIp($request->ip(), $allowlist)) {
            Log::warning('[Webhook] Percobaan akses webhook dari IP di luar allowlist.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak dari alamat IP ini.',
            ], 403);
        }

        $expected = (string) config('services.layanan_madrasah.webhook_secret');
        $given    = (string) $request->header('X-Webhook-Secret', '');

        if (empty($expected)) {
            Log::error('[Webhook] LAYANAN_MADRASAH_WEBHOOK_SECRET belum dikonfigurasi di .env PTSP Online.');
            return response()->json([
                'success' => false,
                'message' => 'Webhook belum dikonfigurasi di server.',
            ], 500);
        }

        if (empty($given) || !hash_equals($expected, $given)) {
            Log::warning('[Webhook] Percobaan akses webhook dengan token tidak valid.', [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Token webhook tidak valid.',
            ], 401);
        }

        // Lapisan kedua: verifikasi HMAC signature dari raw body, supaya body
        // request tidak bisa dimanipulasi (mis. oleh proxy/MITM) tanpa mengubah
        // signature. Header X-Webhook-Signature = hash_hmac('sha256', $rawBody, $secret).
        //
        // CATATAN: opsional untuk masa transisi. Jika Layanan Madrasah belum
        // di-upgrade untuk mengirim header ini, verifikasi ini dilewati (skip)
        // supaya tidak memutus integrasi yang sudah berjalan pada secret-only.
        // Setelah kedua sisi dipastikan mengirim/mewajibkan signature, hapus
        // blok if(empty($signatureGiven)) di bawah agar signature WAJIB ada.
        $signatureGiven = (string) $request->header('X-Webhook-Signature', '');

        if (!empty($signatureGiven)) {
            $rawBody = (string) $request->getContent();
            $signatureExpected = hash_hmac('sha256', $rawBody, $expected);

            if (!hash_equals($signatureExpected, $signatureGiven)) {
                Log::warning('[Webhook] Signature HMAC tidak valid, body kemungkinan dimanipulasi.', [
                    'ip' => $request->ip(),
                    'path' => $request->path(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Signature webhook tidak valid.',
                ], 401);
            }
        }

        return $next($request);
    }
}
