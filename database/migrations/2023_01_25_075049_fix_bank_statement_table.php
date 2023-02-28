<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixBankStatementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_bank_statements', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'fees_payment_id')){
                $table->integer('fees_payment_id')->nullable();
            }
        });
        Schema::table('exam_merit_positions', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'gpa')){
                $table->float('gpa')->nullable();
            }
            if(!Schema::hasColumn($table->getTable(), 'grade')){
                $table->float('grade')->nullable();
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
        Schema::table('sm_bank_statements', function (Blueprint $table) {
            $table->dropColumn('fees_payment_id');
        });
        Schema::table('exam_merit_positions', function (Blueprint $table) {
            $table->dropColumn('gpa');
            $table->dropColumn('grade');
        });
    }
}
