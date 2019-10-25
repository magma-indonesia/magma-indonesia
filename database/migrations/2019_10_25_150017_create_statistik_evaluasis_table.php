<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatistikEvaluasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistik_evaluasis', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code',3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');
            $table->date('start');
            $table->date('end');
            $table->char('nip',18)->index();
            $table->foreign('nip')->references('nip')->on('users');
            $table->bigInteger('hit')->default(0);
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
        Schema::dropIfExists('statistik_evaluasis');
    }
}
