<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('role');
            }

            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('position');
            }
        });

        DB::table('users')->whereNull('position')->update([
            'position' => DB::raw("CASE role WHEN 'hrd' THEN 'HRD' WHEN 'head_department' THEN 'Head Department' WHEN 'gm' THEN 'General Manager' ELSE 'Staff' END")
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'department')) {
                $table->dropColumn('department');
            }

            if (Schema::hasColumn('users', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
};
