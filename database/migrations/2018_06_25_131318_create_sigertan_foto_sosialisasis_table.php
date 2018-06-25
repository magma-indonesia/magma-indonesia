<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigertanFotoSosialisasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigertan_foto_sosialisasis', function (Blueprint $table) {
            $table->increments('id');
            $table->char('noticenumber_id',17)->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_sigertans')->onDelete('cascade');
            $table->string('filename');
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
        Schema::dropIfExists('sigertan_foto_sosialisasis');
    }
}
