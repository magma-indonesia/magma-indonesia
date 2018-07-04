<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGempaterasa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    private function tablegempa($table)
    {
        $table->increments('id');
        $table->integer('var_gempa_id')->unsigned()->index()->nullable();
        $table->foreign('var_gempa_id')->references('id')->on('var_gempas')->onDelete('cascade');    
        $table->char('noticenumber_id',16)->unique()->index();
        $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_vars')->onDelete('cascade');
        $table->smallInteger('jumlah')->default(0);
        $table->float('amin',4,2)->default(0.0);
        $table->float('amax',4,2)->default(0.0);
        $table->float('spmin',4,2)->default(0.0);
        $table->float('spmax',4,2)->default(0.0);
        $table->float('dmin',5,2)->default(0.0);
        $table->float('dmax',5,2)->default(0.0);
        $table->char('skala',40)->nullable();
        $table->timestamps();
    }

    public function up()
    {
        Schema::create('e_trs', function (Blueprint $table) {
            $this->tablegempa($table);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('e_trs');
    }
}