<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarLetusansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('var_letusans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('var_visual_id')->unsigned()->index()->nullable();
            $table->foreign('var_visual_id')->references('id')->on('var_visuals')->onDelete('cascade');
            $table->float('tmin',8,2)->default(0.0);
            $table->float('tmax',8,2)->default(0.0);
            $table->char('wasap')->nullable();
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
        Schema::dropIfExists('var_letusans');
    }
}
