<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLangTypeToSmBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_backups', function (Blueprint $table) {           
            if (!Schema::hasColumn($table->getTable(), 'lang_type')) {
                $table->integer('lang_type')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sm_backups', function (Blueprint $table) {
            //
        });
    }
}
