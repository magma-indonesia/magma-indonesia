<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscribstionStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vona_subscribers', function (Blueprint $table) {
            $table->boolean('real')->default(0)->after('status');
            $table->boolean('exercise')->default(0)->after('real');
            $table->boolean('gladi')->default(0)->after('exercise');
            $table->boolean('pvmbg')->default(0)->after('gladi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vona_subscribers', function (Blueprint $table) {
            //
        });
    }
}
