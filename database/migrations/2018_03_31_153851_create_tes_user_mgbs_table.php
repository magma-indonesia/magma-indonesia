<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTesUserMgbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tes_user_mgbs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nip',18)->unique()->index();
            $table->string('email',40)->nullable();
            $table->string('phone',14)->nullable();
            $table->string('password');
            $table->boolean('status')->default(1);
            $table->string('api_token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('tes_user_mgbs');
    }
}
