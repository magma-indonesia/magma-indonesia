<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagmaRoqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magma_roqs', function (Blueprint $table) {
            $table->increments('id');
            $table->char('noticenumber',17)->unique()->index();
            $table->dateTime('utc');
            $table->float('magnitude',4,2);
            $table->char('type',3);
            $table->float('depth');
            $table->float('latitude',10,6)->nullable();
            $table->float('longitude',10,6)->nullable();
            $table->string('area');
            $table->string('kota_terdekat')->nullable();
            $table->string('mmi')->nullable();
            $table->char('nearest_volcano',100)->nullable();           
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
        Schema::dropIfExists('magma_roqs');
    }
}
