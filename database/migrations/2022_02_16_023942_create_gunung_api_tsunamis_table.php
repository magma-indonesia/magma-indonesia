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
        Schema::create('gunung_api_tsunamis', function (Blueprint $table) {
            $table->id();
            $table->char('gunung_api_code', 3)
                ->index();
            $table->foreign('gunung_api_code')
                ->on('gunung_apis')
                ->references('code')
                ->onDelete('cascade');
            $table->year('tahun');
            $table->boolean('runtuhan_kaldera')->default(0)
                ->comment('Runtuhan kaldera');
            $table->boolean('erupsi')->default(0)
                ->comment('Tsunami karena erupsi bawah permukaan laut');
            $table->boolean('awan_panas_guguran')->default(0)
                ->comment('Tsunami akibat awan panas guguran hingga ke laut');
            $table->boolean('longsoran')->default(0)
                ->comment('Tsunami akibat longsornya tubuh gunung api');
            $table->boolean('penyebab_lainnya')->default(0)
                ->comment('Penyebab lainnya yang tidak masuk dalam kategori sebelumnya');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('gunung_api_tsunamis');
    }
};
