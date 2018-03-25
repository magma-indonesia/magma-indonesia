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
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->date('var_data_date');
            $table->string('periode',11);
            $table->smallInteger('var_perwkt');
            $table->char('obscode_id',4)->index();
            $table->foreign('obscode_id')->references('obscode')->on('pos_pgas');
            $table->integer('statuses_desc_id')->unsigned();
            $table->foreign('statuses_desc_id')->references('id')->on('statuses_desc');
            $table->string('nip_pelapor',18);
            $table->foreign('nip_pelapor')->references('nip')->on('users');
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
