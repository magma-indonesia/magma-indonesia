<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeralatanPemantauansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peralatan_pemantauans', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('bidang', ['ga','gb','gt','ts','lainnya']);

            $table->char('code', 3)->index()->nullable();
            $table->foreign('code')->references('code')->on('ga_dd')->onDelete('set null');

            $table->string('slug_peralatan')
                ->unique()
                ->comment('Gabungan antara code, nama barang, dan 4 random string');

            $table->string('slug_nama_barang')->index();
            $table->foreign('slug_nama_barang')
                ->references('slug_nama_barang')
                ->on('katalog_barangs');

            $table->string('nama_stasiun')->nullable();
            $table->string('kode_stasiun')->nullable();

            $table->enum('transmisi', ['analog', 'digital'])->nullable();

            $table->string('tahun_instalasi', 4);
            $table->string('bulan_instalasi', 2)->nullable();
            $table->string('tanggal_instalasi', 2)->nullable();
            $table->string('nomor_inventaris')->nullable();

            $table->float('altitude')->nullable();
            $table->float('latitude', 10, 6)->nullable();
            $table->float('longitude', 10, 6)->nullable();

            $table->longText('deskripsi')->nullable();
            $table->string('kepemilikan_lahan')->nullable();
            $table->longText('pencapaian_lokasi')->nullable();

            $table->char('province_id', 2);
            $table->foreign('province_id')->references('id')->on(config('laravolt.indonesia.table_prefix') . 'provinces');

            $table->char('city_id', 4)->nullable();
            $table->foreign('city_id')->references('id')->on(config('laravolt.indonesia.table_prefix') . 'cities');

            $table->char('district_id', 7)->nullable();
            $table->foreign('district_id')->references('id')->on(config('laravolt.indonesia.table_prefix') . 'districts');

            $table->char('village_id', 10)->nullable();
            $table->foreign('village_id')->references('id')->on(config('laravolt.indonesia.table_prefix') . 'villages');

            $table->boolean('is_active')->default(0);

            $table->char('nip', 18)->index()->nullable();
            $table->foreign('nip')
                ->references('nip')->on('users')
                ->onDelete('set null');

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
        Schema::dropIfExists('peralatan_pemantauans');
    }
}
