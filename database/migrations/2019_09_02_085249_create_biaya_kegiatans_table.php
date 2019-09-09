<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiayaKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biaya_kegiatans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('detail_kegiatan_id')
                ->unsigned()
                ->index();
            $table->foreign('detail_kegiatan_id')
                ->references('id')
                ->on('detail_kegiatans')
                ->onDelete('cascade');
            $table->integer('upah')->default(0);
            $table->integer('bahan')->default(0);
            $table->integer('carter')->default(0);
            $table->integer('bahan_lainnya')->default(0);
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
        Schema::dropIfExists('biaya_kegiatans');
    }
}
