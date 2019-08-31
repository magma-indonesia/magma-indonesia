<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jenis_kegiatan_id')
                ->unsigned()
                ->index();
            $table->foreign('jenis_kegiatan_id')
                ->references('id')
                ->on('jenis_kegiatans');
            $table->year('tahun')->default(1970);
            $table->string('unique',7)->unique();
            $table->integer('target_jumlah')->default(0);
            $table->bigInteger('target_anggaran')->default(0);
            $table->char('nip_kortim',18)->index();
            $table->foreign('nip_kortim')
                ->references('nip')
                ->on('users');
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
        Schema::dropIfExists('kegiatans');
    }
}
