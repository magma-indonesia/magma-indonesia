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
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->date('var_data_date');
            $table->enum('periode',['00:00-24:00','00:00-06:00','06:00-12:00','12:00-18:00','18:00-24:00','00:00-12:00','12:00-24:00']);
            $table->enum('var_perwkt',[6,12,24]);
            $table->char('obscode_id',4)->index();
            $table->foreign('obscode_id')->references('obscode')->on('pos_pgas');
            $table->enum('status',['1','2','3','4']);
            $table->integer('rekomendasi_id')->unsigned();
            $table->foreign('rekomendasi_id')->references('id')->on('var_rekomendasis');
            $table->char('nip_pelapor',18)->index();
            $table->foreign('nip_pelapor')->references('nip')->on('users');
            $table->index(['code_id','var_data_date']);
            $table->index(['obscode_id','var_data_date']);
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
