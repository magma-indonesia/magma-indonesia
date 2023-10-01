<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKatalogBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('katalog_barangs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug_nama_barang')->unique();
            $table->string('nama_barang');
            $table->string('brand')->nullable()->comment('Merek barang');
            $table->string('model')
                ->comment('Jenis atau model barang')
                ->nullable();
            $table->float('harga')->nullable();
            $table->string('satuan')->nullable();
            $table->text('url')->nullable();

            $table->longText('deskripsi')->nullable();
            $table->longText('spesifikasi')->nullable();

            $table->char('nip', 18)->index()->nullable();
            $table->foreign('nip')
                ->references('nip')->on('users')
                ->onDelete('set null');

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
        Schema::dropIfExists('katalog_barangs');
    }
}
