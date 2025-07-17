<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('kunjungan', function (Blueprint $table) {
        $table->unsignedBigInteger('dibuka_oleh')->nullable()->after('user_id');
    });
}

public function down(): void
{
    Schema::table('kunjungan', function (Blueprint $table) {
        $table->dropColumn('dibuka_oleh');
    });
}

};
