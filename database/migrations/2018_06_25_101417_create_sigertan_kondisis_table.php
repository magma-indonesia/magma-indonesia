<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigertanKondisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigertan_kondisis', function (Blueprint $table) {
            $table->increments('id');
            $table->char('noticenumber_id',17)->unique()->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_sigertans')->onDelete('cascade');
            $table->char('prakiraan_kerentanan',36)->nullable();
            $table->char('tipe_gerakan',36)->nullable();
            $table->char('material',36)->nullable();
            $table->float('arah_longsoran',8,2)->default(0);
            $table->float('panjang_total',8,2)->default(0);
            $table->float('lebar_massa',8,2)->default(0);
            $table->float('panjang_massa',8,2)->default(0);
            $table->float('ketebalan_massa',8,2)->default(0);
            $table->float('lebar_bidang',8,2)->default(0);
            $table->float('panjang_bidang',8,2)->default(0);
            $table->float('ketebalan_bidang',8,2)->default(0);
            $table->text('faktor_penyebab')->nullable();
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
        Schema::dropIfExists('sigertan_kondisis');
    }
}
