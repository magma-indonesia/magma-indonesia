<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGempadominan extends Migration
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
        $table->string('noticenumber_id',16);
        $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_vars')->onDelete('cascade');
        $table->smallInteger('jumlah')->default(0);
        $table->float('amin',8,2)->default(0.0);
        $table->float('amax',8,2)->default(0.0);
        $table->float('adom',8,2)->default(0.0);
        $table->timestamps();
        $table->softDeletes();
    }

    public function up()
    {
        Schema::create('e_mtr', function (Blueprint $table) {
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
        Schema::dropIfExists('e_mtr');
    }
}
