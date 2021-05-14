<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagmaVensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magma_vens', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->char('code_id', 3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->dateTimeTz('datetime_utc');
            $table->enum('timezone', ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura'])
                ->default('Asia/Jakarta');
            $table->boolean('visibility')->default(0);
            $table->integer('height')->default(0);
            $table->json('warna_asap')->nullable();
            $table->json('intensitas')->nullable();
            $table->json('arah_asap')->nullable();
            $table->float('amplitudo', 6, 2)->default(0);
            $table->float('durasi', 8, 2)->default(0);
            $table->enum('status', ['1', '2', '3', '4']);
            $table->boolean('visibility_apg')->default(0);
            $table->float('distance')->default(0.0);
            $table->integer('height_guguran')->default(0);
            $table->json('arah_guguran')->nullable();
            $table->string('foto_letusan')->nullable();
            $table->string('foto_apg')->nullable();
            $table->string('old_photo')->nullable();
            $table->text('informasi_lainnya')->nullable();
            $table->integer('rekomendasi_id')->unsigned();
            $table->foreign('rekomendasi_id')->references('id')->on('var_rekomendasis');
            $table->char('nip_pelapor', 18)->index();
            $table->foreign('nip_pelapor')->references('nip')->on('users');
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
        Schema::dropIfExists('magma_vens');
    }
}