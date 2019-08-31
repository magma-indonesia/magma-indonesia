<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_kegiatans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kegiatan_id')
                ->unsigned()
                ->index();
            $table->foreign('kegiatan_id')
                ->references('id')
                ->on('kegiatans');
            $table->char('code_id',3)
                ->nullable()
                ->index();
            $table->foreign('code_id')
                ->references('code')
                ->on('ga_dd');
            $table->string('lokasi_lainnya')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('proposal')->nullable();
            $table->string('laporan')->nullable();
            $table->char('nip_ketua',18)->index();
            $table->foreign('nip_ketua')->references('nip')->on('users');          
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
        Schema::dropIfExists('detail_kegiatans');
    }
}
