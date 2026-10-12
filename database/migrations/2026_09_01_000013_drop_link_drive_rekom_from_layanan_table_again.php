<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            if (Schema::hasColumn('layanan', 'link_drive_rekom')) {
                $table->dropColumn('link_drive_rekom');
            }
        });
    }

    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            if (!Schema::hasColumn('layanan', 'link_drive_rekom')) {
                $table->string('link_drive_rekom')->nullable()->after('kode_layanan');
            }
        });
    }
};
