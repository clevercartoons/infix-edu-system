<?php

use App\Traits\DatabaseTableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnActiveStatusForAll extends Migration
{
    use DatabaseTableTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $withRecords = $this->tableWithRecordId();
        foreach($withRecords as $getTable)
        {
            Schema::table($getTable, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'active_status')) {
                    $table->integer('active_status')->nullable()->default(1);
                }
            });
        }
        
        Schema::table('student_records', function (Blueprint $table) {
            if (!Schema::hasColumn($table->getTable(), 'active_status')) {
                $table->integer('active_status')->nullable()->default(1);
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
        //
    }
}
