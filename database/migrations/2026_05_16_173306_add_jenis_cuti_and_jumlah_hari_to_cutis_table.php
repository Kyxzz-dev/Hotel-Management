<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cutis', function (Blueprint $table) {
            if (!Schema::hasColumn('cutis', 'jenis_cuti')) {
                $table->string('jenis_cuti')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('cutis', 'jumlah_hari')) {
                $table->integer('jumlah_hari')->default(1)->after('tanggal_selesai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cutis', function (Blueprint $table) {
            if (Schema::hasColumn('cutis', 'jenis_cuti')) {
                $table->dropColumn('jenis_cuti');
            }

            if (Schema::hasColumn('cutis', 'jumlah_hari')) {
                $table->dropColumn('jumlah_hari');
            }
        });
    }
};