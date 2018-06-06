<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagmaVensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magma_vens', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->char('code_id',3)->index();
            $table->foreign('code_id')->references('code')->on('ga_dd');
            $table->date('date');
            $table->time('time');
            $table->boolean('visibility')->default(1);
            $table->integer('height')->default(0);
            $table->char('wasap',100);
            $table->char('intensitas',100);            
            $table->char('arah_asap',100);
            $table->float('amplitudo',8,2)->default(0);
            $table->float('durasi',8,2)->default(0);
            $table->string('photo')->nullable();
            $table->enum('status',['1','2','3','4']);
            $table->text('rekomendasi')->nullable();
            $table->text('lainnya')->nullable();
            $table->char('nip_pelapor',18);
            $table->foreign('nip_pelapor')->references('nip')->on('users');
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
        Schema::dropIfExists('magma_vens');
    }
}
