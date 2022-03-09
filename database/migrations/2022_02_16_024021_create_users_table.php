<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->id('employee_id');
            $table->id('sipeg_id');
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('sipeg_foto')->nullable();
            $table->json('prefix')->nullable();
            $table->json('suffix')->nullable();
            $table->char('nip', 18)->nullable()->unique();
            $table->char('ktp', 16)->nullable()->unique();
            $table->char('phone', 14)->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('email_esdm')->nullable()->unique();

            $table->foreignId('eselon_satu_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            $table->foreignId('eselon_dua_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            $table->foreignId('eselon_tiga_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            $table->foreignId('eselon_empat_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->boolean('is_active')->default(0);
            $table->boolean('is_verified')->default(0);
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
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
};
