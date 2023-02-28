<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropSmPagesSubtitleDuplicate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try{
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sm_pages` DROP INDEX `sm_pages_sub_title_unique`;");
        } catch(\Exception $e){
            info($e);
        }

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
