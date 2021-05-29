<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChoicesSoal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->string('pil1')->after('soal');
            $table->string('pil2')->after('pil1');
            $table->string('pil3')->after('pil2');
            $table->string('pil4')->after('pil3');
            $table->string('pil5')->after('pil4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('soal', function (Blueprint $table) {
            //
        });
    }
}
