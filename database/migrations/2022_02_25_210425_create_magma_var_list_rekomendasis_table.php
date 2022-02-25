<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagmaVarListRekomendasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('magma')->create('magma_var_list_rekomendasis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('magma_var_rekomendasi_id');
            $table->foreign('magma_var_rekomendasi_id')
                ->references('id')->on('magma_var_rekomendasis')
                ->onDelete('cascade');
            $table->longText('rekomendasi');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('magma_var_list_rekomendasis');
    }
}
