<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetrokkenMatenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('getrokkenmaten', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wedstrijddeelnemer_id')->unique();
            $table->unsignedBigInteger('getrokken_maat_id');
            $table->timestamps();

            $table
                ->foreign('wedstrijddeelnemer_id')
                ->references('id')
                ->on('wedstrijddeelnemers');
            $table
                ->foreign('getrokken_maat_id')
                ->references('id')
                ->on('wedstrijddeelnemers');
            $table->unique(
                [
                    'wedstrijddeelnemer_id',
                    'getrokken_maat_id',
                ]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('getrokkenmaten');
    }
}
