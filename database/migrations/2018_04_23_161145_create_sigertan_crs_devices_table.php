<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigertanCrsDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigertan_crs_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('crs_id')->unsigned()->index()->nullable();
            $table->foreign('crs_id')->references('id')->on('sigertan_crs')->onDelete('cascade');
            $table->enum('aplikasi',['MAGMA-SIGERTAN','Magma Indonesia']);
            $table->enum('devices',['ANDROID','IOS','WEB']);
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
        Schema::dropIfExists('sigertan_crs_devices');
    }
}
