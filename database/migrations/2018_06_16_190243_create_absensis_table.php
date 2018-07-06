<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nip_id',18)->index();
            $table->foreign('nip_id')->references('nip')->on('users')->onDelete('cascade');
            $table->char('kantor_id',4)->index();
            $table->foreign('kantor_id')->references('code')->on('kantors');
            $table->dateTime('checkin');
            $table->string('checkin_image')->nullable();
            $table->float('checkin_latitude',10,6)->default(0);
            $table->float('checkin_longitude',10,6)->default(0);
            $table->dateTime('checkout')->nullable();
            $table->string('checkout_image')->nullable();
            $table->float('checkout_latitude',10,6)->nullable();
            $table->float('checkout_longitude',10,6)->nullable();
            $table->integer('distance')->default(0);
            $table->integer('duration')->default(0);
            $table->char('nip_verifikator',18)->nullable();
            $table->foreign('nip_verifikator')->references('nip')->on('users');
            $table->tinyInteger('keterangan')->default(0);
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
        Schema::dropIfExists('absensis');
    }
}
