<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGolongansTable extends Migration
{

    protected $golongans = [
        ['pangkat' => 'Pembina Utama',
          'golongan' => 'IV',
          'ruang' => 'e'],
        ['pangkat' => 'Pembina Utama Madya',
          'golongan' => 'IV',
          'ruang' => 'd'],
        ['pangkat' => 'Pembina Utama Muda',
          'golongan' => 'IV',
          'ruang' => 'c'],
        ['pangkat' => 'Pembina Tingkat I',
          'golongan' => 'IV',
          'ruang' => 'b'],
        ['pangkat' => 'Pembina',
          'golongan' => 'IV',
          'ruang' => 'a'],
        ['pangkat' => 'Penata Tingkat I',
          'golongan' => 'III',
          'ruang' => 'd'],
        ['pangkat' => 'Penata ',
          'golongan' => 'III',
          'ruang' => 'c'],
        ['pangkat' => 'Penata Muda Tingkat I',
          'golongan' => 'III',
          'ruang' => 'b'],
        ['pangkat' => 'Penata Muda',
          'golongan' => 'III',
          'ruang' => 'a'],
        ['pangkat' => 'Pengatur Tingkat I',
          'golongan' => 'II',
          'ruang' => 'd'],
        ['pangkat' => 'Pengatur',
          'golongan' => 'II',
          'ruang' => 'c'],
        ['pangkat' => 'Pengatur Muda Tingkat I',
          'golongan' => 'II',
          'ruang' => 'b'],
        ['pangkat' => 'Pengatur Muda',
          'golongan' => 'II',
          'ruang' => 'a'],
        ['pangkat' => 'Juru Tingkat I',
          'golongan' => 'I',
          'ruang' => 'd'],
        ['pangkat' => 'Juru',
          'golongan' => 'I',
          'ruang' => 'c'],
        ['pangkat' => 'Juru Muda Tingkat I',
          'golongan' => 'I',
          'ruang' => 'b'],
        ['pangkat' => 'Juru Muda',
          'golongan' => 'I',
          'ruang' => 'a'
        ]
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('golongans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pangkat');
            $table->string('golongan');
            $table->string('ruang');
            $table->timestamps();
        });

        DB::table('golongans')->insert($this->golongans);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('golongans');
    }
}
