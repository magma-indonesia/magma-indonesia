<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $viewsTableName = config('eloquent-viewable.models.view.table_name');
        $connection = config('eloquent-viewable.models.view.connection', null);

        if ($connection) {
            Schema::connection($connection)->create($viewsTableName, function (Blueprint $table) {
                $this->createColumns($table);
            });
        } else {
            Schema::create($viewsTableName, function (Blueprint $table) {
                $this->createColumns($table);
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
        $viewsTableName = config('eloquent-viewable.models.view.table_name');

        Schema::dropIfExists($viewsTableName);
    }

    /**
     * Generate the table collumns.
     *
     * @param  Illuminate\Database\Schema\Blueprint  $table
     * @return void
     */
    protected function createColumns(Blueprint $table)
    {
        $table->increments('id');
        $table->string('viewable_type')->index();
        $table->uuid('viewable_id')->index();
        $table->string('visitor');
        $table->timestamp('viewed_at')->useCurrent();
    }
}
