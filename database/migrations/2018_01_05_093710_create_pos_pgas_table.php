<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosPgasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_pgas', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->char('obscode',4)->unique();
            $table->string('observatory');
            $table->string('address')->nullable();
            $table->float('elevation')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
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
        Schema::dropIfExists('pos_pgas');
    }
}
