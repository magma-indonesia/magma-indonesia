<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePressReleaseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('press_release_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('press_release_id')->index();
            $table->foreign('press_release_id')
                ->references('id')->on('press_releases')->onDelete('cascade');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('file_hash', 64)->unique();
            $table->string('collection')->nullable();
            $table->unsignedBigInteger('size');
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
        Schema::dropIfExists('press_release_files');
    }
}
