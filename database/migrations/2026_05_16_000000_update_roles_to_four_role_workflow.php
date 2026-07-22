<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Konversi data lama supaya tidak terkunci setelah role diubah.
        DB::table('users')->where('role', 'admin')->update(['role' => 'gm']);
        DB::table('users')->where('role', 'pegawai')->update(['role' => 'staff']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'gm')->update(['role' => 'admin']);
        DB::table('users')->where('role', 'staff')->update(['role' => 'pegawai']);
    }
};
