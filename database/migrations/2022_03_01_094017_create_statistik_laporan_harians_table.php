<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatistikLaporanHariansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('magma')->hasTable('statistik_laporan_harians')) {
            Schema::create('statistik_laporan_harians', function (Blueprint $table) {
                $table->increments('id');
                $table->date('date')->unique();
                $table->bigInteger('hit')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistik_laporan_harians');
    }
}
