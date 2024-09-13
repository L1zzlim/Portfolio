<?php

// 只移除非主鍵索引

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveNonPrimaryIndexes extends Command
{
    protected $signature = 'db:remove-non-primary-indexes';
    protected $description = 'Remove all indexes except primary keys';

    public function handle()
    {
        $tables = $this->getTables();

        foreach ($tables as $tableInfo) {
            $tableName = is_array($tableInfo) ? $tableInfo['name'] : $tableInfo;
            $this->removeNonPrimaryIndexesFromTable($tableName);
        }

        $this->info('All non-primary indexes have been removed from all tables.');
    }

    private function removeNonPrimaryIndexesFromTable($tableName)
    {
        try {
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
    }

    private function getTables()
    {
        return DB::connection()->getSchemaBuilder()->getTables();
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