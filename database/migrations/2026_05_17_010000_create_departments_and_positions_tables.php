<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('positions')) {
            Schema::create('positions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        $now = now();
        $departments = [
            'Sales & Marketing',
            'Finance',
            'Front Office',
            'Food & Beverage Departement',
            'Housekeeping',
            'Engineering',
            'Wellness',
            'Security',
            'People & Culture',
        ];

        foreach ($departments as $department) {
            DB::table('departments')->updateOrInsert(
                ['name' => $department],
                ['is_active' => true, 'updated_at' => $now, 'created_at' => $now]
            );
        }

        $positions = [
            'HRD',
            'Head Department',
            'General Manager',
            'Staff',
        ];

        foreach ($positions as $position) {
            DB::table('positions')->updateOrInsert(
                ['name' => $position],
                ['is_active' => true, 'updated_at' => $now, 'created_at' => $now]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
        Schema::dropIfExists('departments');
    }
};
