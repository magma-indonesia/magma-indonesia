<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigertanCrsValidasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigertan_crs_validasis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('crs_id')->unsigned()->index()->nullable();
            $table->foreign('crs_id')->references('id')->on('sigertan_crs')->onDelete('cascade');
            $table->boolean('valid')->default(0);
            $table->char('nip_id',18);
            $table->foreign('nip_id')->references('nip')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('sigertan_crs_validasis');
    }
}
