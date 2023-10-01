<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeralatanPemantauanMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peralatan_pemantauan_maintenances', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug_peralatan')->index();
            $table->foreign('slug_peralatan')
                ->references('slug_peralatan')
                ->on('peralatan_pemantauans');

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
        Schema::dropIfExists('peralatan_pemantauan_maintenances');
    }
}
