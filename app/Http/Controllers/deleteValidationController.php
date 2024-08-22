<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;

class deleteValidationController extends Controller
{
    // validating deleting record
    public function checkDeleteItem($tableName, $columnName, $id)
    {
        try {
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            
            if ($tables) {
                foreach ($tables as $table) {
                    if ($table == $tableName) {
                        continue;
                    } else {
                        if (Schema::hasColumn($table, $columnName)) {
                            $valueExists = DB::table($table)->where($columnName, $id)->exists();
                            if ($valueExists) {
                                return response()->json(['message' => 'used']);
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
