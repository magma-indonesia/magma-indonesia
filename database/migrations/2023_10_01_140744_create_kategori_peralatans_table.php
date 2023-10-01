<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKategoriPeralatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_peralatans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug_kategori')->unique();
            $table->string('kategori');
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
        Schema::dropIfExists('kategori_peralatans');
    }
}
