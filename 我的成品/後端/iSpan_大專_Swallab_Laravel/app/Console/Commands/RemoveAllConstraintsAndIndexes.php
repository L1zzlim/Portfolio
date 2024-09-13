<?php

// 移除所有約束和索引，包括主鍵

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveAllConstraintsAndIndexes extends Command
{
    protected $signature = 'db:remove-all-constraints-and-indexes';
    protected $description = 'Remove all constraints and indexes, including primary keys';

    public function handle()
    {
        $tables = $this->getTables();

        foreach ($tables as $tableInfo) {
            $tableName = is_array($tableInfo) ? $tableInfo['name'] : $tableInfo;
            $this->removeAllFromTable($tableName);
        }

        $this->info('All constraints and indexes have been removed from all tables.');
    }

    private function removeAllFromTable($tableName)
    {
        Schema::disableForeignKeyConstraints();

        try {
            // Remove foreign keys
            $foreignKeys = $this->getForeignKeys($tableName);
            foreach ($foreignKeys as $foreignKey) {
                Schema::table($tableName, function ($table) use ($foreignKey) {
                    $table->dropForeign($foreignKey);
                });
                $this->info("Dropped foreign key {$foreignKey} from table {$tableName}");
            }

            // Remove all indexes including primary key
            $indexes = $this->getIndexes($tableName);
            Schema::table($tableName, function ($table) use ($indexes, $tableName) {
                foreach ($indexes as $indexName => $index) {
                    try {
                        if ($indexName === 'PRIMARY') {
                            $table->dropPrimary();
                            $this->info("Dropped primary key from table {$tableName}");
                        } else {
                            $table->dropIndex($indexName);
                            $this->info("Dropped index {$indexName} from table {$tableName}");
                        }
                    } catch (\Exception $e) {
                        $this->warn("Could not drop index {$indexName} from table {$tableName}: {$e->getMessage()}");
                    }
                }
            });
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

    private function getIndexes($tableName)
    {
        $indexes = [];
        $indexInfo = DB::select("SHOW INDEX FROM `{$tableName}`");

        foreach ($indexInfo as $index) {
            $indexes[$index->Key_name] = $index;
        }

        return $indexes;
    }
}