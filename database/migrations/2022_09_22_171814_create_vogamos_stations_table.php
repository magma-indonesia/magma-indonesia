<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVogamosStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('vogamos')->create('vogamos_stations', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 3)->index();
            $table->foreign('code')->references('code')->on('chamber.ga_dd');
            $table->char('station')->index();
            $table->string('station_id')->unique();
            $table->string('nama')->nullable();
            $table->string('dusun')->nullable();
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->float('elevation')->nullable();
            $table->float('latitude', 10, 6)->nullable();
            $table->float('longitude', 10, 6)->nullable();
            $table->text('keterangan')->nullable();
            $table->dateTime('last_data_date')->nullable();
            $table->boolean('is_active')->default(0);
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
        Schema::dropIfExists('vogamos_stations');
    }
}
