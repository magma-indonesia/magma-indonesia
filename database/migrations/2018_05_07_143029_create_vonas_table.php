<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vonas', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->char('noticenumber',11)->index();
            $table->dateTime('issued');
            $table->enum('type',['REAL','EXERCISE']);
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->enum('cu_code',['GREEN','YELLOW','ORANGE','RED']);
            $table->string('location');
            $table->float('elevation');
            $table->text('vas')->comment('Volcanic Activity Summary');
            $table->float('vch_summit')->comment('Volcano Cloud Height Above Summit');
            $table->float('vch_asl')->comment('Volcano Cloud Height Above Sea Level');
            $table->string('vch_other')->comment('Volcano Cloud Height Other Information')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('sent')->default(0);
            $table->char('nip_pelapor',18)->index();
            $table->foreign('nip_pelapor')->references('nip')->on('users');
            $table->char('nip_pengirim',18)->nullable();
            $table->foreign('nip_pengirim')->references('nip')->on('users');
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
        Schema::dropIfExists('vonas');
    }
}
