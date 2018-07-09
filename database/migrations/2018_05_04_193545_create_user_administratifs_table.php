<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAdministratifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_administratifs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('bidang_id')->unsigned()->index();
            $table->foreign('bidang_id')->references('id')->on('user_bidang_descs')->onDelete('cascade');
            $table->date('tanggal_lahir')->nullable();
            $table->char('tempat_lahir',100)->nullable();
            $table->enum('agama',['ISLAM','PROTESTASN','KATOLIK','HINDU','BUDDHA'])->nullable();
            $table->enum('kelamin',['LAKI-LAKI','PEREMPUAN'])->nullable();
            $table->boolean('status_nikah')->default(0);
            $table->integer('jabatan_id')->nullable()->unsigned();
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->onDelete('cascade');
            $table->string('pangkat')->nullable();
            $table->char('golongan',5)->nullable();
            $table->char('pendidikan_terakhir',5)->nullable();
            $table->string('jurusan_terakhir')->nullable();
            $table->char('kantor_id',4)->nullable();
            $table->foreign('kantor_id',4)->references('code')->on('kantors')->onDelete('cascade');
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
        Schema::dropIfExists('user_administratifs');
    }
}
