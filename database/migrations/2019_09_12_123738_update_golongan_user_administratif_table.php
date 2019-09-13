<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGolonganUserAdministratifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_administratifs', function (Blueprint $table) {
            $table->integer('golongan_id')
                    ->nullable()
                    ->unsigned();
            $table->foreign('golongan_id')
                    ->references('id')
                    ->on('golongans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_administratifs', function (Blueprint $table) {
            //
        });
    }
}
