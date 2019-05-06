<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStakeholdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stakeholders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('app_name');
            $table->uuid('uuid')->unique();
            $table->string('organisasi');
            $table->enum('api_type',['private','public','internal','internal-mga','internal-mgt','internal-mgb']);
            $table->string('api_token')->unique();
            $table->string('kontak_nama');
            $table->string('kontak_phone');
            $table->string('kontak_email');
            $table->boolean('status')->default(0);
            $table->timestamp('expired_at');
            $table->char('nip',18)->index();
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
        Schema::dropIfExists('stakeholders');
    }
}
