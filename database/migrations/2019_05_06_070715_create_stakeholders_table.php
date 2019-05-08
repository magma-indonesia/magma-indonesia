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
            $table->string('secret_key')->unique();
            $table->string('kontak_nama');
            $table->char('kontak_phone',14)->nullable();
            $table->char('kontak_email',50);
            $table->boolean('status')->default(0);
            $table->char('nip',18)->index();
            $table->foreign('nip')->references('nip')->on('users');
            $table->timestamp('expired_at');
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
