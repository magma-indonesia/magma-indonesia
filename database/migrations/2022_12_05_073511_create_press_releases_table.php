<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePressReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('press_releases', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('gunung_api')->default(0);
            $table->boolean('gerakan_tanah')->default(0);
            $table->boolean('gempa_bumi')->default(0);
            $table->boolean('tsunami')->default(0);
            $table->string('lainnya')->nullable();
            $table->enum('kategori', [
                'peningkatan tingkat aktivitas',
                'penurunan tingkat aktivitas',
                'peringatan dini',
                'analisis geologi'
            ]);
            $table->char('code_id', 3)
                ->index()->nullable();
            $table->foreign('code_id')
                ->references('code')->on('ga_dd')->onDelete('set null');
            $table->string('judul');
            $table->string('slug');
            $table->longText('deskripsi');
            $table->bigInteger('hit')->default(0);
            $table->char('nip', 18)->index();
            $table->foreign('nip')
                ->references('nip')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('press_releases');
    }
}
