<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserAdministratifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_administratifs', function (Blueprint $table) {
            $table->integer('fungsional_id')
                    ->nullable()
                    ->unsigned();
            $table->foreign('fungsional_id')
                    ->references('id')
                    ->on('fungsionals');
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
