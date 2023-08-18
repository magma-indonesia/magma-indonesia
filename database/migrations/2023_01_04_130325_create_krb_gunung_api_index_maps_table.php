<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKrbGunungApiIndexMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('krb_gunung_api_index_maps', function (Blueprint $table) {
            $table->increments('id');

            $table->char('code', 3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');

            $table->char('krb_code')->index();
            $table->foreign('krb_code')->references('krb_code')->on('krb_gunung_apis');

            $table->char('nomor_lembar_peta');

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
        Schema::dropIfExists('krb_gunung_api_index_maps');
    }
}
