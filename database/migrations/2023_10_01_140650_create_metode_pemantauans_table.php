<?php

use App\MetodePemantauan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CreateMetodePemantauansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metode_pemantauans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug_metode')->unique();
            $table->string('metode');
            $table->longText('deskripsi')->nullable();
            $table->char('nip', 18)->index()->nullable();
            $table->foreign('nip')
                ->references('nip')->on('users')
                ->onDelete('set null');
            $table->timestamps();
        });

        collect([
            'Geofisika',
            'Deformasi',
            'Geokimia',
            'Geologi',
            'Visual',
            'Remote Sensing',
        ])->each(function ($method) {
            MetodePemantauan::create([
                'slug_metode' => Str::slug($method),
                'metode' => $method,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metode_pemantauans');
    }
}
