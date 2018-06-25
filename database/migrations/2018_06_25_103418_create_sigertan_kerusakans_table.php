<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigertanKerusakansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigertan_kerusakans', function (Blueprint $table) {
            $table->increments('id');
            $table->char('noticenumber_id',17)->unique()->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_sigertans')->onDelete('cascade');
            $table->integer('meninggal')->default(0);
            $table->integer('luka')->default(0);
            $table->integer('rumah_rusak')->default(0);
            $table->integer('rumah_hancur')->default(0);
            $table->integer('rumah_terancam')->default(0);
            $table->integer('bangunan_rusak')->default(0);
            $table->integer('bangunan_hancur')->default(0);
            $table->integer('bangunan_terancam')->default(0);
            $table->float('lahan_rusak',8,2)->default(0);
            $table->float('jalan_rusak',8,2)->default(0);
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
        Schema::dropIfExists('sigertan_kerusakans');
    }
}
