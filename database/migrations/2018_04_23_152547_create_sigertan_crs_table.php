<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateSigertanCrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigertan_crs', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 100);
            $table->char('phone', 14);
            $table->char('crs_id', 17)->unique()->index();
            $table->dateTime('waktu_kejadian')->default(Carbon::now())->comment('Waktu kejadian (format: YYYY-MM-DD HH:MM:SS, default: hari ini)');
            $table->enum('zona',['WIB','WITA','WIT']);
            $table->enum('type',['GUNUNG API','GEMPA BUMI','TSUNAMI','GERAKAN TANAH','SEMBURAN LUMPUR, GAS, DAN AIR']);
            $table->char('province_id', 2);
            $table->foreign('province_id')->references('id')->on(config('laravolt.indonesia.table_prefix') . 'provinces');
            $table->char('city_id', 4);
            $table->foreign('city_id')->references('id')->on(config('laravolt.indonesia.table_prefix') . 'cities');
            $table->char('district_id', 7);
            $table->foreign('district_id')->references('id')->on(config('laravolt.indonesia.table_prefix') . 'districts');            
            $table->char('village_id', 10);
            $table->foreign('village_id')->references('id')->on(config('laravolt.indonesia.table_prefix') . 'villages');            
            $table->string('bwd')->comment('Dusun/kampung/alamat');
            $table->float('latitude')->default(0);
            $table->float('longitude')->default(0);
            $table->boolean('brd')->default(0)->comment('Apakah pelapor berada di lokasi berita/kejadian yang dilaporkan (pilihan: 1 atau 0)');
            $table->string('sumber')->comment('Sumber berita (pilihan: "Saya sendiri", "BNPB/BPBD", "Pemda", "Media Massa", "Lainnya, sebutkan:")');
            $table->date('tsc')->default(Carbon::now())->comment('Tanggal sumber berita (default: hari ini)');
            $table->string('ksc')->comment('Keterangan berita/fenomena yang dilihat');
            $table->enum('status',['BARU','DRAFT','TERBIT']);
            $table->float('latitude_user',10,6)->default(0);
            $table->float('longitude_user',10,6)->default(0);
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
        Schema::dropIfExists('sigertan_crs');
    }
}
