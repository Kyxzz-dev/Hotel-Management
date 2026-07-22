<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('positions')) {
            return;
        }

        $now = now();
        $allowedPositions = [
            'HRD',
            'Head Department',
            'General Manager',
            'Staff',
        ];

        DB::table('positions')
            ->whereNotIn('name', $allowedPositions)
            ->delete();

        foreach ($allowedPositions as $position) {
            DB::table('positions')->updateOrInsert(
                ['name' => $position],
                ['is_active' => true, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        if (Schema::hasTable('users')) {
            DB::table('users')
                ->whereNotNull('position')
                ->whereNotIn('position', $allowedPositions)
                ->update(['position' => 'Staff']);
        }
    }

    public function down(): void
    {
        // Tidak mengembalikan posisi lama agar data tetap bersih sesuai struktur role utama hotel.
    }
};
