<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAnggotaKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anggota_kegiatans', function (Blueprint $table) {
            $table->integer('kegiatan_id')
                ->unsigned()
                ->after('id')
                ->nullable()
                ->index();
            $table->foreign('kegiatan_id')
                ->references('id')
                ->on('kegiatans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
