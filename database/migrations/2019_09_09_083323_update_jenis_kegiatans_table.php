<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateJenisKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jenis_kegiatans', function (Blueprint $table) {
            $table->char('code',3)->after('nama')->index();
            $table->foreign('code')->references('code')->on('user_bidang_descs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jenis_kegiatans', function (Blueprint $table) {
            //
        });
    }
}
