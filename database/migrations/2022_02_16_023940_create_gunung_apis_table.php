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
        Schema::create('gunung_apis', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->char('code', 3)->unique();
            $table->char('name', 40)->index();
            $table->string('slug')->index();
            $table->string('alias')->nullable();
            $table->boolean('has_dome')->default(0);
            $table->boolean('has_crater')->default(0);
            $table->boolean('is_national_issue')->default(0);
            $table->boolean('is_submarine_volcano')->default(0);
            $table->boolean('is_hydro_volcano')->default(0);
            $table->boolean('is_repose')->default(0)->comment('Periode ulang letusan repose <5 tahun');
            $table->enum('type', ['A', 'B', 'C']);
            $table->enum('time_zone', ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura']);
            $table->float('elevation')->nullable();
            $table->float('latitude', 10, 6)->nullable();
            $table->float('longitude', 10, 6)->nullable();
            $table->char('smithsonian', 6)->nullable()->index();
            $table->boolean('is_published')->default(1);
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
        Schema::dropIfExists('gunung_apis');
    }
};
