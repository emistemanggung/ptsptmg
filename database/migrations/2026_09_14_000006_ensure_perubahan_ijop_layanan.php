<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $namaLayanan = 'Pelayanan  Perubahan, Penggantian, Atau Kerusakan  Izin  Operasional  Madrasah (PENMAD)';

        $row = DB::table('layanan')->where('id', 57)->first();

        if ($row) {
            DB::table('layanan')->where('id', 57)->update([
                'nama_layanan' => $namaLayanan,
                'deskripsi' => 'Layanan PTSP otomatis untuk perubahan, penggantian, atau kerusakan Izin Operasional Madrasah (PENMAD).',
                'kode_layanan' => 'PERUBAHAN_IJOP',
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('layanan')->insert([
            'id' => 57,
            'nama_layanan' => $namaLayanan,
            'deskripsi' => 'Layanan PTSP otomatis untuk perubahan, penggantian, atau kerusakan Izin Operasional Madrasah (PENMAD).',
            'kode_layanan' => 'PERUBAHAN_IJOP',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('layanan')->where('id', 57)->where('kode_layanan', 'PERUBAHAN_IJOP')->delete();
    }
};
