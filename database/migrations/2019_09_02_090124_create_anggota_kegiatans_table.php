<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggotaKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota_kegiatans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('detail_kegiatan_id')
                ->unsigned()
                ->index();
            $table->foreign('detail_kegiatan_id')
                ->references('id')
                ->on('detail_kegiatans');
            $table->char('nip_anggota',18)->index();
            $table->foreign('nip_anggota')->references('nip')->on('users');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('uang_harian')->default(0);
            $table->integer('penginapan_tigapuluh')->default(0);
            $table->integer('penginapan_penuh')->default(0);
            $table->integer('jumlah_hari_menginap')->default(0);
            $table->integer('uang_transport')->default(0);
            $table->char('nip_kortim',18)->index();
            $table->foreign('nip_kortim')->references('nip')->on('users'); 
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
        Schema::dropIfExists('anggota_kegiatans');
    }
}
