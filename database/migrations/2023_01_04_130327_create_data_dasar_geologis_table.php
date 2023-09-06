<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataDasarGeologisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_dasar_geologis', function (Blueprint $table) {
            $table->increments('id');

            $table->char('code', 3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');

            $table->longText('umum')->nullable();
            $table->longText('morfologi')->nullable();
            $table->longText('stratigrafi')->nullable();
            $table->longText('struktur_geologi')->nullable();
            $table->longText('petrografi')->nullable();

            $table->char('nip', 18)->index();
            $table->foreign('nip')->references('nip')->on('users');

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
        Schema::dropIfExists('data_dasar_geologis');
    }
}
