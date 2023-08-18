<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKrbGunungApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('krb_gunung_apis', function (Blueprint $table) {
            $table->increments('id');

            $table->char('krb_code')->unique();

            $table->char('code', 3)->index();
            $table->foreign('code')->references('code')->on('ga_dd');

            $table->longText('indonesia');
            $table->longText('english')->nullable();
            $table->string('created_by')->nullable();
            $table->integer('year_published');
            $table->bigInteger('scale');

            $table->string('mapped_by')->nullable();
            $table->integer('year_mapped')->nullable();
            $table->string('revised_by')->nullable();
            $table->integer('year_revision')->nullable();
            $table->string('text_by')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->string('manuscript_by')->nullable();
            $table->string('digitized_by')->nullable();

            $table->boolean('checked')->default(0);

            $table->char('nip', 18)->index();
            $table->foreign('nip')->references('nip')->on('users');

            $table->boolean('is_active')->default(0);

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
        Schema::dropIfExists('krb_gunung_apis');
    }
}
