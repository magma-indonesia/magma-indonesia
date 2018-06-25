<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigertanGeologisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigertan_geologis', function (Blueprint $table) {
            $table->increments('id');
            $table->char('noticenumber_id',17)->unique()->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_sigertans')->onDelete('cascade');
            $table->string('bentang_alam')->nullable();
            $table->string('kemiringan_lereng')->nullable();
            $table->float('kemiringan_lereng_rata',8,2)->default(0);
            $table->float('ketinggian',8,2)->default(0);
            $table->string('jenis_batuan')->nullable();
            $table->string('formasi_batuan')->nullable();
            $table->string('struktur_geologi')->nullable();
            $table->char('jenis_tanah',100)->nullable();
            $table->float('ketebalan_tanah',8,2)->default(0);
            $table->string('tipe_keairan')->nullable();
            $table->float('muka_air_tanah',8,2)->default(0);
            $table->string('tata_guna_lahan')->nullable();
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
        Schema::dropIfExists('sigertan_geologis');
    }
}
