<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGempasp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    private function tablegempa($table)
    {
        $table->increments('id');
        $table->string('noticenumber_id',16);
        $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_vars')->onDelete('cascade');
        $table->smallInteger('jumlah')->default(0);
        $table->float('amin',8,2)->default(0.0);
        $table->float('amax',8,2)->default(0.0);
        $table->float('spmin',8,2)->default(0.0);
        $table->float('spmax',8,2)->default(0.0);
        $table->float('dmin',8,2)->default(0.0);
        $table->float('dmax',8,2)->default(0.0);
        $table->timestamps();
        $table->softDeletes();
    }

    public function up()
    {
        Schema::create('e_tej', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_vta', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_tel', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_dev', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_hyb', function (Blueprint $table) {
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
        Schema::dropIfExists('e_tej');
        Schema::dropIfExists('e_vta');
        Schema::dropIfExists('e_tel');
        Schema::dropIfExists('e_dev');
        Schema::dropIfExists('e_hyb');
    }
}
