<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeVerifikasiPetugasColumnType extends Migration
{
    public function up()
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->string('verifikasi_petugas')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->integer('verifikasi_petugas')->nullable()->change();
        });
    }
}
