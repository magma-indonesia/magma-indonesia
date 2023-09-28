<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVogamosDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('vogamos')->create('vogamos_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('station_id')->index();
            $table->foreign('station_id')
                ->references('station_id')
                ->on('vogamos_stations');
            $table->dateTime('datetime')->index();
            $table->float('suhu_tanah_satu');
            $table->float('suhu_tanah_dua');
            $table->float('suhu_udara');
            $table->float('h2s');
            $table->float('tekanan_udara');
            $table->float('co2');
            $table->float('suhu_dalam_box');
            $table->float('tegangan_baterai');
            $table->index(['station_id','datetime']);
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
        Schema::dropIfExists('vogamos_datas');
    }
}
