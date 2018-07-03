<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->char('nip',18)->unique()->index();
            $table->char('email',40)->nullable();
            $table->char('phone',14)->nullable();
            $table->string('password');
            $table->boolean('status')->default(1);
            $table->string('api_token')->nullable();
            $table->unique(['nip','deleted_at']);
            $table->unique(['email','deleted_at']);
            $table->unique(['phone','deleted_at']);
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
        Schema::dropIfExists('users');
    }
}
