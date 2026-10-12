<?php
/**
 * TEMPEL blok ini di routes/web.php (PTSP Online), di luar grup middleware
 * 'auth' (webhook dipanggil server-to-server, tanpa session login).
 *
 * Tambahkan use statement di bagian atas file:
 *   use App\Http\Controllers\WebhookController;
 */

Route::prefix('webhook')->middleware(['verify.webhook', 'throttle:60,1'])->group(function () {
    Route::post('permohonan/tiket', [WebhookController::class, 'receiveTiket'])
        ->name('webhook.permohonan.tiket');

    Route::post('permohonan/rekomendasi', [WebhookController::class, 'updateRekomendasi'])
        ->name('webhook.permohonan.rekomendasi');
});
