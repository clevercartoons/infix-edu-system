<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToSmSmsGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_sms_gateways', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'type')) {
                $table->string('type', 5)->nullable()->default('com');
            }
        });
        Schema::table('sm_sections', function (Blueprint $table) {
            if(!Schema::hasColumn($table->getTable(), 'un_academic_id')) {
                $table->integer('un_academic_id')->nullable()->unsigned();
            }
        });
        $sql = ("INSERT INTO `infix_module_infos` (`id`, `module_id`, `parent_id`, `type`, `is_saas`, `name`, `route`, `lang_name`, `icon_class`, `active_status`, `created_by`, `updated_by`, `school_id`, `created_at`, `updated_at`) VALUES
        
        (15201, 3, 61, '2', 0,'Multi Class Student','student.multi-class-student','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'),
        (15205, 3, 61, '2', 0,'Delete Student Record','student.delete-student-record','delete_student_record','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'),
        (15209, 3, 61, '2', 0,'UnAssign Student','unassigned_student','unassigned_student','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22')
        ");
         DB::insert($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sm_sms_gateways', function (Blueprint $table) {
            $dropColumns = ['type'];
            $table->dropColumn($dropColumns);
        });
        Schema::table('sm_sections', function (Blueprint $table) {
            $sectionDropColumns = ['un_academic_id'];
            $table->dropColumn($sectionDropColumns);
        });
    }
}
