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
            $table->char('noticenumber', 11)->unique()->nullable();
            $table->uuid('ven_uuid')->nullable();
            $table->foreign('ven_uuid')->references('uuid')->on('magma_vens')->onDelete('cascade');
            $table->dateTimeTz('issued');
            $table->enum('type', ['REAL', 'EXERCISE'])->default('REAL');
            $table->char('code_id', 3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->boolean('is_visible')->default(0);
            $table->boolean('is_continuing')->default(0);
            $table->enum('current_code', ['GREEN', 'YELLOW', 'ORANGE', 'RED']);
            $table->enum('previous_code', ['unassigned', 'GREEN', 'YELLOW', 'ORANGE', 'RED']);
            $table->float('ash_height')->default(0)->comment('Volcano Cloud Height Above Summit');
            $table->json('ash_color')->nullable();
            $table->json('ash_intensity')->nullable();
            $table->json('ash_directions')->nullable();
            $table->float('amplitude', 6, 2)->default(0);
            $table->float('duration', 8, 2)->default(0);
            $table->string('ash_information')->comment('Volcano Cloud Height Other Information')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_sent')->default(0);
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
