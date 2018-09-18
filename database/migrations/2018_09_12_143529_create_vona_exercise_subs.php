<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVonaExerciseSubs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vona_exercise_subscriber', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->char('email',50)->unique()->index();
            $table->boolean('status')->default(0);
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
        Schema::create('vona_exercise_subscriber', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->char('email',50)->unique()->index();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }
}
