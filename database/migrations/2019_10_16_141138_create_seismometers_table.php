<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeismometersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seismometers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->char('code',3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');
            $table->string('lokasi')->nullable();
            $table->char('station',4)->index();
            $table->char('channel',3)->index();
            $table->char('network',3)->default('VG')->index();
            $table->char('location',2)->default('00')->index();
            $table->char('scnl',15)->unique();
            $table->float('elevation')->nullable();
            $table->float('latitude',10,6)->nullable();
            $table->float('longitude',10,6)->nullable();
            $table->boolean('is_active')->default(1);
            $table->bigInteger('hit')->default(0);
            $table->boolean('published')->default(0);
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
        Schema::dropIfExists('seismometers');
    }
}
