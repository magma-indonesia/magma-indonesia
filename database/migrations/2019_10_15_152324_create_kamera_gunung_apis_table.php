<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKameraGunungApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamera_gunung_apis', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->char('code',3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');
            $table->string('lokasi')->nullable();
            $table->string('url')->nullable()->unique();
            $table->float('elevation')->nullable();
            $table->float('latitude',10,6)->nullable();
            $table->float('longitude',10,6)->nullable();
            $table->bigInteger('hit')->default(0);
            $table->boolean('publish')->default(0);
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
        Schema::dropIfExists('kamera_gunung_apis');
    }
}
