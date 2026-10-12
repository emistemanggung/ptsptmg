# PTSPTMG — Merge Webhook + Tiket Baru

Paket ini mempertahankan `ptsptmg-main` sebagai basis utama, lalu memasukkan
hanya komponen integrasi webhook dari `ptsptmg-main_belum_fix`.

## Tiket
Webhook **menggunakan generator tiket baru dari `Permohonan::generateNoTiket()`**
yang sudah ada di `ptsptmg-main`. Jadi tiket dari webhook mengikuti format tiket
baru PTSP, bukan format lama `TKT-XXXXXXXXXXXXX`.

## Webhook
Endpoint:
- `POST /webhook/permohonan/tiket`
- `POST /webhook/permohonan/rekomendasi`

Keamanan:
- `X-Webhook-Secret`
- opsional `X-Webhook-Signature` (HMAC SHA-256)
- opsional IP allowlist

## Database
Tambahan migration:
`2026_09_22_000001_sync_layanan_madrasah_webhook.php`

Migration dibuat idempotent agar dapat menangani hosting yang belum memasang
kolom webhook maupun yang sudah pernah memasang migration webhook versi lama.

## File tiket
File pencarian/tampilan tiket dari `ptsptmg-main` **tidak diganti** dengan versi
`belum_fix`. Basis tiket tetap `ptsptmg-main`.
