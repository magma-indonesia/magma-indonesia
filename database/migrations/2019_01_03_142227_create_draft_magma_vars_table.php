<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDraftMagmaVarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_magma_vars', function (Blueprint $table) {
            $table->increments('id');
            $table->char('noticenumber',16)->unique()->index();
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->char('nip_pelapor',18)->index();
            $table->foreign('nip_pelapor')->references('nip')->on('users');
            $table->json('var')->nullable();
            $table->json('var_visual')->nullable();
            $table->json('var_klimatologi')->nullable();
            $table->json('var_gempa')->nullable();
            $table->boolean('var_saved')->default(0);
            $table->boolean('var_visual_saved')->default(0);
            $table->boolean('var_klimatologi_saved')->default(0);
            $table->boolean('var_gempa_saved')->default(0);
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
        Schema::dropIfExists('draft_magma_vars');
    }
}
