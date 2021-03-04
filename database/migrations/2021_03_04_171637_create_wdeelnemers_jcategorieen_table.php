<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWdeelnemersJcategorieenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wdeelnemers_jcategorieen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wedstrijddeelnemer_id')->unique();
            $table->unsignedBigInteger('jeugdcategorie_id');
            $table->timestamps();

            $table->foreign('wedstrijddeelnemer_id')->references('id')->on('wedstrijddeelnemers');
            $table->foreign('jeugdcategorie_id')->references('id')->on('jeugdcategorieen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wdeelnemers_jcategorieen');
    }
}
