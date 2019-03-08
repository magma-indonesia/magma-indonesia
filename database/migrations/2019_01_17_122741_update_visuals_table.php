<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVisualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('var_visuals', function (Blueprint $table) {
            $table->string('filename_0')->nullable();
            $table->string('filename_1')->nullable();
            $table->string('filename_2')->nullable();
            $table->string('filename_3')->nullable();
            $table->string('file_old')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('var_visuals', function (Blueprint $table) {
            //
        });
    }
}
