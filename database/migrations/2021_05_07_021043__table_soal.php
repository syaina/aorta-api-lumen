<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableSoal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_soal');
            $table->string('url_gambar')->nullable();
            $table->string('soal');
            $table->string('jawaban');
            $table->string('id_bab')->references('id_bab')->on('bab');
            $table->string('id_materi')->references('id_materi')->on('bab');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soal');
    }
}
