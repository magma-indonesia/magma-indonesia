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
            $table->char('noticenumber_id',16)->unique()->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_vars')->onDelete('cascade');
            $table->char('cuaca',50)->nullable();
            $table->decimal('curah_hujan',3,2)->default(0.0);
            $table->char('kecangin',32)->nullable();
            $table->char('arahangin',100)->nullable();
            $table->decimal('suhumin',3,2)->default(0.0);
            $table->decimal('suhumax',3,2)->default(0.0);
            $table->decimal('lembabmin',3,2)->default(0.0);
            $table->decimal('lembabmax',3,2)->default(0.0);
            $table->decimal('tekmin',4,2)->default(0.0);
            $table->decimal('tekmax',4,2)->default(0.0);
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
        Schema::dropIfExists('var_klimatologis');
    }
}
