<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGaddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ga_dd', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name',40)->index();
            $table->char('code',3)->unique();
            $table->char('alias',200)->nullable();
            $table->tinyInteger('tzone');
            $table->char('zonearea',4);
            $table->char('district',100);
            $table->char('province',100);
            $table->char('nearest_city',100)->nullable();
            $table->char('division',5);
            $table->char('volc_type',2);
            $table->float('elevation');
            $table->float('latitude',10,6);
            $table->float('longitude',10,6);
            $table->char('smithsonian_id', 6)->nullable();
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
        Schema::dropIfExists('ga_dd');
    }
}
