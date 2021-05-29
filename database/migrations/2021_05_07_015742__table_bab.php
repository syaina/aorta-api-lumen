<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableBab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bab', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_bab')->unique();
            $table->string('judul_bab');
            $table->string('url_gambar');
            $table->string('id_materi')->references('id_materi')->on('materi');
            $table->string('slug');
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
        Schema::dropIfExists('bab');
    }
}
