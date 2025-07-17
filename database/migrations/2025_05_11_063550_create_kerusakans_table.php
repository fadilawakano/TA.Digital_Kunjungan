<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKerusakansTable extends Migration
{
    public function up()
    {
        Schema::create('kerusakans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kelas');
            $table->string('kategori'); // lab biologi, kimia, dsb
            $table->text('deskripsi');
            $table->string('status')->default('belum dikonfirmasi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kerusakans');
    }
}
