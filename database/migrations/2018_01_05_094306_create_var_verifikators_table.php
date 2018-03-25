<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarVerifikatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('var_verifikators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('noticenumber_id',16)->unique()->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_vars')->onDelete('cascade');
            $table->string('nip_id',18)->nullable();
            $table->foreign('nip_id')->references('nip')->on('users')->onDelete('cascade');;
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('var_verifikators');
    }
}
