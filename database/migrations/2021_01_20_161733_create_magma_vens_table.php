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
            $table->enum('jenis', ['lts','apg'])->index();
            $table->dateTimeTz('datetime_utc');
            $table->enum('timezone', ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura'])
                ->default('Asia/Jakarta');
            $table->boolean('is_continuing')->default(0);
            $table->boolean('is_visible')->default(0);
            $table->integer('ash_height')->default(0);
            $table->json('ash_color')->nullable();
            $table->json('ash_intensity')->nullable();
            $table->json('ash_directions')->nullable();
            $table->float('amplitude', 6, 2)->default(0);
            $table->float('duration', 8, 2)->default(0);
            $table->integer('seismometer_id')->unsigned();
            $table->foreign('seismometer_id')->references('id')->on('seismometers');
            $table->enum('status', ['1', '2', '3', '4']);
            $table->float('distance')->default(0.0);
            $table->json('arah_guguran')->nullable();
            $table->string('foto_letusan')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('old_photo')->nullable();
            $table->text('informasi_lainnya')->nullable();
            $table->integer('rekomendasi_id')->unsigned();
            $table->foreign('rekomendasi_id')->references('id')->on('var_rekomendasis');
            $table->boolean('has_vona')->default(0);
            $table->char('nip_pelapor', 18)->index();
            $table->foreign('nip_pelapor')->references('nip')->on('users');
            $table->dateTimeTz('published_at')->nullable();
            $table->dateTimeTz('blasted_at')->nullable();
            $table->dateTimeTz('broadcasted_at')->nullable();
            $table->enum('broadcasted_level',['1','2','3'])->nullable();
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
