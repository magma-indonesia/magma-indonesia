<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBencanaGeologiPendahuluansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bencana_geologi_pendahuluans', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code',3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');
            $table->text('pendahuluan');
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
        Schema::dropIfExists('bencana_geologi_pendahuluans');
    }
}
