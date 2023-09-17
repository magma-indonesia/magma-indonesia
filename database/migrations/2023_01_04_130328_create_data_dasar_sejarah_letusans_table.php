<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataDasarSejarahLetusansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_dasar_sejarah_letusans', function (Blueprint $table) {
            $table->increments('id');

            $table->char('code', 3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');

            $table->string('time_label', 3)->default('AD')
                ->comment('AD, BC, dkk');

            $table->string('start_year', 4);
            $table->string('start_month', 2)->nullable();
            $table->string('start_date', 2)->nullable();
            $table->string('start_hour', 2)->nullable();
            $table->string('start_minute', 2)->nullable();
            $table->string('start_second', 2)->nullable();

            $table->string('end_year', 4);
            $table->string('end_month', 2)->nullable();
            $table->string('end_date', 2)->nullable();
            $table->string('end_hour', 2)->nullable();
            $table->string('end_minute', 2)->nullable();
            $table->string('end_second', 2)->nullable();

            $table->string('timezone')->nullable();

            $table->longText('description');

            $table->boolean('is_checked')->default(0);

            $table->char('nip', 18)->index();
            $table->foreign('nip')->references('nip')->on('users');

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
        Schema::dropIfExists('data_dasar_sejarah_letusans');
    }
}
