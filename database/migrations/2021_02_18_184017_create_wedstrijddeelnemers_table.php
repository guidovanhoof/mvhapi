<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWedstrijddeelnemersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wedstrijddeelnemers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wedstrijd_id');
            $table->unsignedBigInteger('deelnemer_id');
            $table->boolean('is_gediskwalificeerd');
            $table->text('opmerkingen')->nullable();
            $table->timestamps();

            $table->foreign('wedstrijd_id')->references('id')->on('wedstrijden');
            $table->foreign('deelnemer_id')->references('id')->on('deelnemers');

            $table->unique(['wedstrijd_id', 'deelnemer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wedstrijddeelnemers');
    }
}
