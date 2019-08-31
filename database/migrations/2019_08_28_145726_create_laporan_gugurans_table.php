<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaporanGuguransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_gugurans', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->datetime('datetime');
            $table->integer('jarak_luncur')->default(0);
            $table->integer('volume')->default(0);
            $table->integer('tinggi_kolom')->default(0);
            $table->float('kecepatan')->default(0);
            $table->json('arah_guguran')->nullable();
            $table->json('arah_angin')->nullable();
            $table->integer('durasi')->default(0);
            $table->float('amplitudo')->default(0);
            $table->text('keterangan_lainnya')->nullable();
            $table->string('photo_1')->nullable();
            $table->string('photo_2')->nullable();
            $table->string('photo_3')->nullable();
            $table->string('photo_4')->nullable();
            $table->char('nip_pelapor',18);
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
        Schema::dropIfExists('laporan_gugurans');
    }
}
