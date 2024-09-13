<?php

// 只移除外鍵約束及其相關索引

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveForeignKeysAndIndexes extends Command
{
    protected $signature = 'db:remove-foreign-keys-and-indexes';
    protected $description = 'Remove all foreign keys and their associated indexes';

    public function handle()
    {
        $tables = $this->getTables();

        foreach ($tables as $tableInfo) {
            $tableName = is_array($tableInfo) ? $tableInfo['name'] : $tableInfo;
            $this->removeForeignKeysAndIndexesFromTable($tableName);
        }

        $this->info('All foreign keys and their associated indexes have been removed from all tables.');
    }

    private function removeForeignKeysAndIndexesFromTable($tableName)
    {
        Schema::disableForeignKeyConstraints();

        try {
            $foreignKeys = $this->getForeignKeys($tableName);
            foreach ($foreignKeys as $foreignKey) {
                Schema::table($tableName, function ($table) use ($foreignKey, $tableName) {
                    try {
                        $table->dropForeign($foreignKey);
                        $this->info("Dropped foreign key {$foreignKey} from table {$tableName}");

                        // Try to remove the associated index
                        $indexName = str_replace(['_foreign'], '', $foreignKey);
                        if (Schema::hasIndex($tableName, $indexName)) {
                            $table->dropIndex($indexName);
                            $this->info("Dropped associated index {$indexName} from table {$tableName}");
                        }
                    } catch (\Exception $e) {
                        $this->warn("Could not drop foreign key or index {$foreignKey} from table {$tableName}: {$e->getMessage()}");
                    }
                });
            }
        } catch (\Exception $e) {
            $this->error("Error processing table {$tableName}: " . $e->getMessage());
        }

        Schema::enableForeignKeyConstraints();
    }

    private function getTables()
    {
        return DB::connection()->getSchemaBuilder()->getTables();
    }

    private function getForeignKeys($tableName)
    {
        $foreignKeys = [];
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [DB::connection()->getDatabaseName(), $tableName]);

        foreach ($constraints as $constraint) {
            $foreignKeys[] = $constraint->CONSTRAINT_NAME;
        }

        return $foreignKeys;
    }
}