<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_catalogs', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');
            $table->char('scnl', 15)->index();
            $table->foreign('scnl')->references('scnl')->on('seismometers');
            $table->char('code_event', 15)->index();
            $table->foreign('code_event')->references('code')->on('event_types');
            $table->dateTimeTz('p_datetime_utc')->index();
            $table->dateTimeTz('s_datetime_utc')->nullable();
            $table->dateTimeTz('p_datetime_local')->index();
            $table->dateTimeTz('s_datetime_local')->nullable();
            $table->float('p_s_duration')->nullable();
            $table->enum('timezone', ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura'])->default('Asia/Jakarta');
            $table->float('duration')->comment('seconds');
            $table->float('maximum_amplitude');
            $table->text('other_information')->nullable();
            $table->char('nip', 18)->index();
            $table->foreign('nip')->references('nip')->on('users');
            $table->unique(['scnl', 'p_datetime_utc']);
            $table->index(['scnl', 'code_event']);
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
        Schema::dropIfExists('event_catalogs');
    }
}
