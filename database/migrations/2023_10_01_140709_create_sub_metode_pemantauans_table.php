<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubMetodePemantauansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_metode_pemantauans', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug_metode')->index();
            $table->foreign('slug_metode')
                ->references('slug_metode')->on('metode_pemantauans');

            $table->string('slug_sub_metode')->unique();
            $table->string('sub_metode');
            $table->longText('deskripsi')->nullable();
            $table->char('nip', 18)->index()->nullable();
            $table->foreign('nip')
                ->references('nip')->on('users')
                ->onDelete('set null');
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
        Schema::dropIfExists('sub_metode_pemantauans');
    }
}
