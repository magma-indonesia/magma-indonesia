<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarKlimatologisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('var_klimatologis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('noticenumber_id',16)->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_vars')->onDelete('cascade');
            $table->char('cuaca',50)->nullable();
            $table->char('kecangin',32)->nullable();
            $table->char('arahangin',100)->nullable();
            $table->float('suhumin',4,2)->default(0.0);
            $table->float('suhumax',4,2)->default(0.0);
            $table->float('lembabmin',4,2)->default(0.0);
            $table->float('lembabmax',4,2)->default(0.0);
            $table->float('tekmin',5,2)->default(0.0);
            $table->float('tekmax',5,2)->default(0.0);
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
        Schema::dropIfExists('var_klimatologis');
    }
}
