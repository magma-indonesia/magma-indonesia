<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVarRekomendasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('var_rekomendasis', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->smallInteger('status');
            $table->text('rekomendasi');
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
        Schema::dropIfExists('var_rekomendasis');
    }
}
