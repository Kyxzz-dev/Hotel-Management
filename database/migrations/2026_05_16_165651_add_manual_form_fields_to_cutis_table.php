<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cutis', function (Blueprint $table) {
            $table->string('position')->nullable();
            $table->string('department')->nullable();

            $table->date('last_day_of_work')->nullable();
            $table->date('first_day_of_work')->nullable();

            $table->integer('entitlement')->default(0);
            $table->integer('balance_before')->default(0);
            $table->integer('request_day')->default(0);
            $table->integer('balance_after')->default(0);

            $table->string('person_in_charge')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('cutis', function (Blueprint $table) {
            $table->dropColumn([
                'position',
                'department',
                'last_day_of_work',
                'first_day_of_work',
                'entitlement',
                'balance_before',
                'request_day',
                'balance_after',
                'person_in_charge',
                'remarks',
                'approved_at',
                'rejected_at',
            ]);
        });
    }
};