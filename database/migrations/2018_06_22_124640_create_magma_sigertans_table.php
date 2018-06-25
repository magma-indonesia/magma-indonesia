<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagmaSigertansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magma_sigertans', function (Blueprint $table) {
            $table->increments('id');
            $table->char('crs_id', 17)->unique()->index();
            $table->foreign('crs_id')->references('crs_id')->on('sigertan_crs')->onDelete('cascade');
            $table->char('noticenumber',17)->unique()->index();
            $table->string('peta_lokasi')->nullable();
            $table->string('peta_geologi')->nullable();
            $table->string('peta_situasi')->nullable();
            $table->enum('disposisi',['DESKWORK','LAPANGAN']);
            $table->char('nip_ketua',18);
            $table->foreign('nip_ketua')->references('nip')->on('users');
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
        Schema::dropIfExists('magma_sigertans');
    }
}
