<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengajuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->increments('id');
            $table->char('nip_id')->index();
            $table->foreign('nip_id')->references('nip')->on('users');
            $table->enum('topik',['ALAT MONITORING','INVENTARIS POS','CUTI/IJIN','KEPEGAWAIAN']);
            $table->text('pertanyaan');
            $table->string('foto_pertanyaan')->nullable();
            $table->enum('follow_up',['BELUM DIPROSES','SEDANG DIPROSES','SUDAH DIPROSES']);
            $table->char('nip_pemroses')->nullable();
            $table->foreign('nip_pemroses')->references('nip')->on('users');
            $table->text('jawaban')->nullable();
            $table->string('foto_jawaban')->nullable();
            $table->dateTime('answered_at')->nullable();
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
        Schema::dropIfExists('pengajuans');
    }
}
