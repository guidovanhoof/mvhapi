<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReeksenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reeksen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wedstrijd_id');
            $table->unsignedTinyInteger('nummer');
            $table->time('aanvang');
            $table->time('duur')->nullable();
            $table->unsignedInteger('gewicht_zak');
            $table->text('opmerkingen')->nullable();
            $table->timestamps();

            $table->foreign('wedstrijd_id')->references('id')->on("wedstrijden");
            $table->unique(['wedstrijd_id', 'nummer']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reeks');
    }
}
