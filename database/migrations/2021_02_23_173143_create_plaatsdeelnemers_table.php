<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaatsdeelnemersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plaatsdeelnemers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plaats_id');
            $table->unsignedBigInteger('wedstrijddeelnemer_id');
            $table->unsignedTinyInteger('is_weger');
            $table->timestamps();

            $table->foreign("plaats_id")->references('id')->on('plaatsen');
            $table->foreign("wedstrijddeelnemer_id")->references('id')->on('wedstrijddeelnemers');

            $table->unique(['plaats_id', 'wedstrijddeelnemer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plaatsdeelnemers');
    }
}
