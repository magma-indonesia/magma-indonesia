<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vonas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ga_dd_id')->unsigned()->index()->nullable();
            $table->foreign('ga_dd_id')->references('id')->on('ga_dd');
            $table->char('noticenumber',10)->index();
            $table->char('issued',14)->index();
            $table->enum('type',['real','exercise']);
            $table->char('cu_avcode',8);
            $table->char('pre_avcode',8);
            $table->char('location');
            $table->char('area',100);
            $table->integer('elevation_ft');
            $table->string('volcanic_act_summ');
            $table->integer('vc_height_ft');
            $table->integer('vc_height_meter');
            $table->string('other_vc_info');
            $table->boolean('sent')->default(0);
            $table->integer('users_id')->unsigned()->index()->nullable();
            $table->foreign('users_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vonas');
    }
}
