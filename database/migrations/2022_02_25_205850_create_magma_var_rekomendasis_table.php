<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagmaVarRekomendasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('magma')->create('magma_var_rekomendasis', function (Blueprint $table) {
            $table->increments('id');
            $table->char('ga_code', 3)->index();
            $table->foreign('ga_code')->references('ga_code')->on('ga_dd');
            $table->enum('status', ['1', '2', '3', '4']);
            $table->date('date');
            $table->boolean('is_active')->default(0);
            $table->unique(['ga_code','status','date']);
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
        Schema::dropIfExists('magma_var_rekomendasis');
    }
}
