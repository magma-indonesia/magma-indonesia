<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePetaKrbGunungApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peta_krb_gunung_apis', function (Blueprint $table) {
            $table->integer('tahun')
                ->after('code')
                ->nullable()
                ->index();
            $table->bigInteger('size')->after('filename')->nullable();
            $table->bigInteger('large_size')->after('size')->nullable();
            $table->bigInteger('medium_size')->after('large_size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peta_krb_gunung_apis', function (Blueprint $table) {
            $table->dropColumn('tahun');
            $table->dropColumn('filename_large');
            $table->dropColumn('filename_medium');
            $table->dropColumn('thumbnail');
            $table->dropColumn('size');
        });
    }
}
