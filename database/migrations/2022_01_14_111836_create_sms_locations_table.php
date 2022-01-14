<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code_id', 3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->char('kode_kabupaten')->index();
            $table->string('nama_kabupaten');
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
        Schema::dropIfExists('sms_locations');
    }
}
