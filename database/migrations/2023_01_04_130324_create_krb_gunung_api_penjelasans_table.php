<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKrbGunungApiPenjelasansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('krb_gunung_api_penjelasans', function (Blueprint $table) {
            $table->increments('id');

            $table->char('krb_code')->index();
            $table->foreign('krb_code')->references('krb_code')->on('krb_gunung_apis');

            $table->char('code', 3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');

            $table->integer('zona_krb');
            $table->longText('indonesia');
            $table->longText('english')->nullable();

            $table->longText('area_id')->nullable();
            $table->longText('area_en')->nullable();

            $table->float('radius')->nullable();
            $table->longText('radius_id')->nullable();
            $table->longText('radius_en')->nullable();

            $table->char('nip', 18)->index();
            $table->foreign('nip')->references('nip')->on('users');

            $table->unique(['krb_code', 'zona_krb']);

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
        Schema::dropIfExists('krb_gunung_api_penjelasans');
    }
}
