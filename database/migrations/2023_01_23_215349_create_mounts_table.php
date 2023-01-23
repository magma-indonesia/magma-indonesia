<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mounts', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 3)
                ->unique();
            $table->foreign('code')
                ->references('code')->on('ga_dd')
                ->onDelete('cascade');
            $table->enum('type', ['gas', 'thermal']);
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
        Schema::dropIfExists('mounts');
    }
}
