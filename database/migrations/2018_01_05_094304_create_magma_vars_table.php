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
            $table->char('noticenumber',16)->unique()->index();
            $table->dateTime('var_issued');
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->date('var_data_date');
            $table->char('periode',11);
            $table->smallInteger('var_perwkt');
            $table->char('obscode_id',4)->index();
            $table->foreign('obscode_id')->references('obscode')->on('pos_pgas');
            $table->enum('status',['1','2','3','4']);
            $table->char('nip_pelapor',18)->index();
            $table->foreign('nip_pelapor')->references('nip')->on('users');
            $table->index(['code_id','var_data_date']);
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
        Schema::dropIfExists('magma_vars');
    }
}
