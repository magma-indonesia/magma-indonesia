<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gunung_api_provinces', function (Blueprint $table) {
            $table->id();

            $table->char('gunung_api_code', 3)
                ->index();
            $table->foreign('gunung_api_code')
                ->on('gunung_apis')
                ->references('code')
                ->onDelete('cascade');

            $table->char('province_id', 2)
                ->index();
            $table->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onDelete('cascade');

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
        Schema::dropIfExists('gunung_api_provinces');
    }
};
