<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKatalogBarangFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('katalog_barang_files', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug_nama_barang')->index();
            $table->foreign('slug_nama_barang')
                ->references('slug_nama_barang')
                ->on('katalog_barangs');

            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('file_hash', 64);
            $table->string('collection')->nullable();
            $table->text('overview')->nullable();

            $table->unsignedBigInteger('size');

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
        Schema::dropIfExists('katalog_barang_gambars');
    }
}
