<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alats', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->integer('jenis_id')->unsigned()->index();
            $table->foreign('jenis_id')->references('id')->on('alat_jenis');
            $table->char('kode_alat',4)->nullable();
            $table->string('nama_alat');
            $table->float('latitude',10,7);
            $table->float('longitude',10,7);
            $table->float('elevasi',6,2);
            $table->boolean('status')->default(0);
            $table->char('nip',18)->index();
            $table->foreign('nip')->references('nip')->on('users');
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
        Schema::dropIfExists('alats');
    }
}
