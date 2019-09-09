<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBiayaKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('biaya_kegiatans', function (Blueprint $table) {
            $table->dropForeign('biaya_kegiatans_detail_kegiatan_id_foreign');
            $table->foreign('detail_kegiatan_id')
                ->references('id')
                ->on('detail_kegiatans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('biaya_kegiatans', function (Blueprint $table) {
            //
        });
    }
}
