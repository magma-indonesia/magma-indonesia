<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKategoriPeralatansKatalogBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_peralatans_katalog_barangs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug_kategori')->index();
            $table->foreign('slug_kategori')
                ->references('slug_kategori')
                ->on('kategori_peralatans');

            $table->string('slug_nama_barang')->index();
            $table->foreign('slug_nama_barang')
                ->references('slug_nama_barang')
                ->on('katalog_barangs');

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
        Schema::dropIfExists('kategori_peralatans_katalog_barangs');
    }
}
