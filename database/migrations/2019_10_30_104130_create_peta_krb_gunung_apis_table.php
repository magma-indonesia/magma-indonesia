<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetaKrbGunungApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peta_krb_gunung_apis', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code',3)->index();
            $table->foreign('code')
                ->references('code')
                ->on('ga_dd');
            $table->string('filename');
            $table->text('keterangan')->nullable();
            $table->char('nip',18)->index();
            $table->foreign('nip')->references('nip')->on('users');
            $table->bigInteger('hit')->default(0);
            $table->boolean('published')->default(0);
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
        Schema::dropIfExists('peta_krb_gunung_apis');
    }
}
