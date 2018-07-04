<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGempanormal extends Migration
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
        $table->float('dmin',5,2)->default(0.0);
        $table->float('dmax',5,2)->default(0.0);
        $table->timestamps();
    }

    public function up()
    {
        Schema::create('e_vlp', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_dpt', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_vtb', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_lof', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_tor', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_hrm', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_tre', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_hbs', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_gtb', function (Blueprint $table) {
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
        Schema::dropIfExists('e_vlp');
        Schema::dropIfExists('e_dpt');
        Schema::dropIfExists('e_vtb');
        Schema::dropIfExists('e_lof');
        Schema::dropIfExists('e_tor');
        Schema::dropIfExists('e_hrm');
        Schema::dropIfExists('e_tre');
        Schema::dropIfExists('e_hbs');
        Schema::dropIfExists('e_gtb');
    }
}
