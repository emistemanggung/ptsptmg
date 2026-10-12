<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('layanan', 'link_drive_rekom')) {
            Schema::table('layanan', function (Blueprint $table) {
                $table->string('link_drive_rekom')->nullable()->after('kode_layanan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('layanan', 'link_drive_rekom')) {
            Schema::table('layanan', function (Blueprint $table) {
                $table->dropColumn('link_drive_rekom');
            });
        }
    }
};
