<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMountsThermalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mounts_thermals', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('mount_id');
            $table->foreign('mount_id')
                ->references('id')
                ->on('mounts');

            $table->char('code', 3)
                ->index()->nullable();
            $table->foreign('code')
                ->references('code')->on('ga_dd')
                ->onDelete('set null');

            $table->dateTimeTz('datetime')->index();
            $table->date('date')->index();
            $table->time('time')->index();
            $table->float('value')->comment('N pixel');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('mounts_thermals');
    }
}
