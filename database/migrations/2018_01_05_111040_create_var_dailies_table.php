<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('var_dailies', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code_id',3)->unique();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->char('noticenumber_id',16)->unique()->index();
            $table->foreign('noticenumber_id')->references('noticenumber')->on('magma_vars')->onDelete('cascade');
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
        Schema::dropIfExists('var_dailies');
    }
}
