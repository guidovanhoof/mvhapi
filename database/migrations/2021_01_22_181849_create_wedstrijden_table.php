<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWedstrijdenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wedstrijden', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kalender_id');
            $table->unsignedSmallInteger('nummer')->nullable();
            $table->date('datum')->unique();
            $table->string('omschrijving');
            $table->string('sponsor')->nullable();
            $table->time('aanvang')->default('13:30:00');
            $table->unsignedBigInteger('wedstrijdtype_id');
            $table->text('opmerkingen')->nullable();
            $table->timestamps();

            $table->foreign('kalender_id')->references('id')->on('kalenders');
            $table->foreign('wedstrijdtype_id')->references('id')->on('wedstrijdtypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wedstrijden');
    }
}
