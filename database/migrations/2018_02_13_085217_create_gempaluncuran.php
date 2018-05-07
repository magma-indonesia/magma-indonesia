<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGempaluncuran extends Migration
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
        $table->float('amin',8,2)->default(0.0);
        $table->float('amax',8,2)->default(0.0);
        $table->float('dmin',8,2)->default(0.0);
        $table->float('dmax',8,2)->default(0.0);
        $table->float('rmin',8,2)->default(0.0);
        $table->float('rmax',8,2)->default(0.0);
        $table->text('arah')->nullable();
        $table->timestamps();
        $table->softDeletes();
    }

    public function up()
    {
        Schema::create('e_apg', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_apl', function (Blueprint $table) {
            $this->tablegempa($table);
        });

        Schema::create('e_gug', function (Blueprint $table) {
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
        Schema::dropIfExists('e_apg');
        Schema::dropIfExists('e_apl');
        Schema::dropIfExists('e_gug');
    }
}