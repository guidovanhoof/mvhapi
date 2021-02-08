<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaatsenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plaatsen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("reeks_id");
            $table->unsignedTinyInteger("nummer");
            $table->text("opmerkingen")->nullable();
            $table->timestamps();

            $table->foreign("reeks_id")->references("id")->on("reeksen");
            $table->unique(["reeks_id", "nummer"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plaatsen');
    }
}
