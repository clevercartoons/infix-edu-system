<?php

use App\SmCurrency;
use App\SmSchool;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCurrencyFormatToSmCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_currencies', function (Blueprint $table) {   
                $table->string('currency_type', 2)->nullable()->default(2)->comments('C=code, S= symbol');
                $table->string('currency_position', 2)->nullable()->default(2)->comments('S=suffix, P= prefix'); 
                $table->boolean('space')->nullable()->default(true);   
                $table->integer('decimal_digit')->nullable();
                $table->string('decimal_separator', 1)->nullable();
                $table->string('thousand_separator')->nullable();
        });
        $schools = SmSchool::all();
        foreach ($schools as $school) {
            SmCurrency::where('school_id', $school->id)->update([
                'currency_type'=>'S',
                'currency_position'=>'S',
                'space'=>true,
                'decimal_digit'=>2,
                'decimal_separator'=>".",
                'thousand_separator'=>",",
            ]);
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sm_currencies', function (Blueprint $table) {
            $columns = ['currency_type', 'currency_position','space','decimal_digit','decimal_separator','thousand_separator'];
            $table->dropColumn($columns);
        });
    }
}
