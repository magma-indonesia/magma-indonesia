<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagmaVarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magma_vars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('noticenumber',16)->unique();
            $table->dateTime('var_issued');
            $table->char('code_id',3);
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->date('var_data_date');
            $table->string('periode',20);
            $table->string('var_perwkt',10);
            $table->char('obscode_id',4);
            $table->foreign('obscode_id')->references('obscode')->on('pos_pgas');
            $table->string('nip_pelapor',18);
            $table->foreign('nip_pelapor')->references('nip')->on('users');
            $table->string('nip_pj',18)->nullable();
            $table->string('nip_verifikator',18)->nullable();
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
        Schema::dropIfExists('magma_vars');
    }
}
