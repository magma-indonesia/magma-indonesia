<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBencanaGeologisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bencana_geologis', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('urutan')->default(0);
            $table->char('code',3)->unique();
            $table->foreign('code')->references('code')->on('ga_dd');
            $table->integer('bencana_geologi_pendahuluan_id')
                ->unsigned()
                ->index();
            $table->foreign('bencana_geologi_pendahuluan_id')
                ->references('id')
                ->on('bencana_geologi_pendahuluans');
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('bencana_geologis');
    }
}
