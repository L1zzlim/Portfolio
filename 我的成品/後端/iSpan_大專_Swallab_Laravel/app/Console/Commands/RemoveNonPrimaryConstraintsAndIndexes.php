<?php

// 移除除主鍵外的所有約束和索引

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveNonPrimaryConstraintsAndIndexes extends Command
{
    protected $signature = 'db:remove-non-primary-constraints-and-indexes';
    protected $description = 'Remove all constraints and indexes except primary keys';

    public function handle()
    {
        $tables = $this->getTables();

        foreach ($tables as $tableInfo) {
            $tableName = is_array($tableInfo) ? $tableInfo['name'] : $tableInfo;
            $this->removeNonPrimaryFromTable($tableName);
        }

        $this->info('All non-primary constraints and indexes have been removed from all tables.');
    }

    private function removeNonPrimaryFromTable($tableName)
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

            // Remove non-primary indexes
            $indexes = $this->getIndexes($tableName);
            Schema::table($tableName, function ($table) use ($indexes, $tableName) {
                foreach ($indexes as $indexName => $index) {
                    if ($indexName !== 'PRIMARY') {
                        try {
                            $table->dropIndex($indexName);
                            $this->info("Dropped index {$indexName} from table {$tableName}");
                        } catch (\Exception $e) {
                            $this->warn("Could not drop index {$indexName} from table {$tableName}: {$e->getMessage()}");
                        }
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
            if ($index->Key_name !== 'PRIMARY') {
                $indexes[$index->Key_name] = $index;
            }
        }

        return $indexes;
    }
}