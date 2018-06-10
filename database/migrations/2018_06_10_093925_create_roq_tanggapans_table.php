<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoqTanggapansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roq_tanggapans', function (Blueprint $table) {
            $table->increments('id');
            $table->char('noticenumber_id',17)->unique()->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_roqs')->onDelete('cascade');
            $table->string('judul');
            $table->boolean('tsunami')->default(0);
            $table->text('pendahuluan');
            $table->text('kondisi_wilayah');
            $table->text('mekanisme');
            $table->text('dampak');
            $table->text('rekomendasi');
            $table->text('sumber');
            $table->string('maplink')->nullable();
            $table->char('nip_pelapor',18);
            $table->foreign('nip_pelapor')->references('nip')->on('users')->onDelete('cascade');
            $table->char('nip_pemeriksa',18)->nullable();
            $table->foreign('nip_pemeriksa')->references('nip')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('roq_tanggapans');
    }
}
