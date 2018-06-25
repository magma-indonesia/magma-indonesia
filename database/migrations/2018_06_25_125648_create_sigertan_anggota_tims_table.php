<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigertanAnggotaTimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigertan_anggota_tims', function (Blueprint $table) {
            $table->increments('id');
            $table->char('noticenumber_id',17)->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_sigertans')->onDelete('cascade');
            $table->char('nip_id',18)->index();
            $table->foreign('nip_id')->references('nip')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('sigertan_anggota_tims');
    }
}
