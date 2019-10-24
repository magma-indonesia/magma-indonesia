<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiveSeismogramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_seismograms', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code',3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');
            $table->integer('seismometer_id')
                ->unsigned()
                ->index();
            $table->foreign('seismometer_id')
                ->references('id')
                ->on('seismometers')
                ->onDelete('cascade');
            $table->string('filename');
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
        Schema::dropIfExists('live_seismograms');
    }
}
