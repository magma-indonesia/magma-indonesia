<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagmaVarRekomendasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('magma')->hasTable('magma_var_rekomendasis')) {
            Schema::connection('magma')->create('magma_var_rekomendasis', function (Blueprint $table) {
                $table->increments('id');
                $table->string('ga_code', 3)
                    ->charset('utf8')
                    ->collation('utf8_unicode_ci')
                    ->index();
                $table->foreign('ga_code')->references('ga_code')->on('ga_dd');
                $table->enum('status', ['1', '2', '3', '4'])
                    ->charset('utf8')
                    ->collation('utf8_unicode_ci');
                $table->date('date');
                $table->boolean('is_active')->default(0);
                $table->unique(['ga_code', 'status', 'date']);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('magma_var_rekomendasis');
    }
}
