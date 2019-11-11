<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatistikLoginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistik_logins', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->char('nip',18)->index();
            $table->foreign('nip')->references('nip')->on('users');
            $table->bigInteger('hit')->default(0);
            $table->ipAddress('ip_address');
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
        Schema::dropIfExists('statistik_logins');
    }
}
