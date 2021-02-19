<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGewichtenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gewichten', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("plaats_id");
            $table->unsignedInteger("gewicht");
            $table->unsignedTinyInteger("is_geldig");
            $table->timestamps();

            $table->foreign("plaats_id")->references("id")->on("plaatsen");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gewichten');
    }
}
