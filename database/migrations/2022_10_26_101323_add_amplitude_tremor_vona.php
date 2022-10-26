<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmplitudeTremorVona extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vonas', function (Blueprint $table) {
            $table->float('amplitude_tremor', 6, 2)->default(0)->after('amplitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vonas', function (Blueprint $table) {
            //
        });
    }
}
