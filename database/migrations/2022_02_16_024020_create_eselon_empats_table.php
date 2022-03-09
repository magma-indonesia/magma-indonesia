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
        Schema::create('eselon_empats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eselon_satu_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('eselon_dua_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('eselon_tiga_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('slug')->index();
            $table->string('deskripsi_tugas')->nullable();
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
        Schema::dropIfExists('eselon_empats');
    }
};
