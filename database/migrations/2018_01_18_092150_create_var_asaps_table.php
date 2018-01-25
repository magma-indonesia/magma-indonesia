<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarAsapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('var_asaps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('var_visual_id')->unsigned()->index()->nullable();
            $table->foreign('var_visual_id')->references('id')->on('var_visuals')->onDelete('cascade');
            $table->float('tasap_min',8,2)->default(0.0);
            $table->float('tasap_max',8,2)->default(0.0);
            $table->char('wasap')->nullable();
            $table->char('intasap')->nullable();
            $table->char('tekasap')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('var_asaps');
    }
}
