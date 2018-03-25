<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarGempasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('var_gempas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('noticenumber_id',16)->unique()->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_vars')->onDelete('cascade');
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
        Schema::dropIfExists('var_gempas');
    }
}
