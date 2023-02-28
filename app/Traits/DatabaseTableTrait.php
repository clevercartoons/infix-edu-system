<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DatabaseTableTrait
{
    public function tableWithRecordId(): array
    {
        $tables = $this->getAllTables();
        $withRecords = [];
        $db = "Tables_in_".env('DB_DATABASE');
        foreach($tables as $table) {
            if ((Schema::hasColumn($table->{$db}, 'record_id'))) {
                $withRecords[] = $table->{$db};
            }
            if ((Schema::hasColumn($table->{$db}, 'student_record_id'))) {
                $withRecords[] = $table->{$db};
            }
        }
        return $withRecords;
    }
    public function tableWithRecordIdActiveStatus(): array
    {
        $tables = $this->getAllTables();
        $recordWithActiveStatus = [];
        $db = "Tables_in_".env('DB_DATABASE');
        foreach($tables as $table) {
            if ((Schema::hasColumns($table->{$db}, ['record_id', 'active_status']))) {
                $recordWithActiveStatus[] = $table->{$db};
            }
            if ((Schema::hasColumns($table->{$db}, ['student_record_id', 'active_status']))) {
                $recordWithActiveStatus[] = $table->{$db};
            }
        }
        return $recordWithActiveStatus;
    }
    private function getAllTables()
    {
        return DB::select('SHOW TABLES');
    }
}
