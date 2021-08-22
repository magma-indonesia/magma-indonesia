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
            $table->uuid('uuid')->unique();
            $table->char('noticenumber', 11)->nullable();
            $table->uuid('ven_uuid')->nullable();
            $table->foreign('ven_uuid')->references('uuid')->on('magma_vens')->onDelete('cascade');
            $table->dateTimeTz('issued');
            $table->enum('type', ['REAL', 'EXERCISE'])->default('REAL');
            $table->char('code_id', 3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->enum('cu_code', ['GREEN', 'YELLOW', 'ORANGE', 'RED']);
            $table->enum('prev_code', ['unassigned', 'GREEN', 'YELLOW', 'ORANGE', 'RED']);
            $table->string('location');
            $table->text('vas')->comment('Volcanic Activity Summary');
            $table->float('vch_summit')->default(0)->comment('Volcano Cloud Height Above Summit');
            $table->float('vch_asl')->comment('Volcano Cloud Height Above Sea Level');
            $table->string('vch_other')->comment('Volcano Cloud Height Other Information')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('sent')->default(0);
            $table->char('nip_pelapor', 18)->index();
            $table->foreign('nip_pelapor')->references('nip')->on('users');
            $table->char('nip_pengirim', 18)->nullable();
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
