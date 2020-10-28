<?php

namespace old;

use DB;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Throwable;

class OldTableSeeder extends Seeder
{
    protected $db;
    protected $importedCount = 0;
    protected $chunkSize = 20000;
    protected $maxInserts;
    protected $tableName = 'Table';
    protected $defaultOrder = 'id';
    protected $pdo;

    public function __construct()
    {
        $this->db = DB::connection('oldRedentradas');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        echo "\e[0;33mImporting:\e[0m {$this->tableName}\n";
        $startTime = microtime(true);
        try {
            $this->prepareRecords();
            $this->db->table($this->tableName)->orderBy($this->defaultOrder)->chunk($this->chunkSize, function ($results) {
                $chunkStartTime = microtime(true);
                $chunkStart = $this->importedCount;
                foreach ($results as $result) {
                    $this->createRecords($result);
                    $this->importedCount++;
                }
                $chunkTime = round(microtime(true) - $chunkStartTime, 2);
                echo "\e[0;32mChunking and importing:\e[0m {$chunkStart}-{$this->importedCount} {$this->tableName} ({$chunkTime} seconds)\n";
                // Break the chunk
                if ($this->maxInserts && $this->importedCount >= $this->maxInserts) {
                    return false;
                }
            });

            $runTime = round(microtime(true) - $startTime, 2);
            echo "\e[0;32mTotal imported:\e[0m {$this->importedCount} {$this->tableName} ({$runTime} seconds)\n";
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            echo "\e[0;31mError:\e[0m {$this->tableName} import is not available\n";
        }
    }

    /**
     * Helper used before creating the records.
     */
    public function prepareRecords(): void
    {
    }

    /**
     * Helper to create the records.
     *
     * @param $result
     */
    public function createRecords($result): void
    {
    }

    /**
     * @param null $chunkOverridedSize
     */
    public function massiveImport($chunkOverridedSize = null): void
    {
        echo "\e[0;33mImporting:\e[0m {$this->tableName}\n";
        $startTime = microtime(true);
        $chunkSize = (isset($chunkOverridedSize)) ? $chunkOverridedSize : $this->chunkSize;
        try {
            $this->prepareRecords();
            $this->db->disableQueryLog();
            $this->db->table($this->tableName)->orderBy($this->defaultOrder)->chunk($chunkSize, function (&$results) use ($chunkSize) {
                $chunkStartTime = microtime(true);
                $chunkStart = $this->importedCount;
                $this->createRecords($results);
                $this->importedCount += $chunkSize;
                $chunkTime = round(microtime(true) - $chunkStartTime, 2);
                echo "\e[0;32mChunking and importing:\e[0m {$chunkStart}-{$this->importedCount} {$this->tableName} ({$chunkTime} seconds)\n";
            });

            $runTime = round(microtime(true) - $startTime, 2);
            echo "\e[0;32mTotal imported:\e[0m {$this->importedCount} {$this->tableName} ({$runTime} seconds)\n";
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            echo "\e[0;31mError:\e[0m {$this->tableName} import is not available\n";
            dd($exception);
        }
    }

    public function massiveImportUnchunked()
    {
        echo "\e[0;33mImporting unchunked:\e[0m {$this->tableName}\n";
        $startTime = microtime(true);
        try {
            $this->prepareRecords();
            $this->db->disableQueryLog();
            $results = $this->db->table($this->tableName)->orderBy($this->defaultOrder)->get();
            $this->createRecords($results);
            $runTime = round(microtime(true) - $startTime, 2);
            echo "\e[0;32mTotal imported:\e[0m " . count($results) . " {$this->tableName} ({$runTime} seconds)\n";
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            echo "\e[0;31mError:\e[0m {$this->tableName} import is not available\n";
            dd($exception);
        }
    }

    /**
     * @param $table
     * @param $chunkMaxSize
     * @param $array
     */
    public function chunkInsert($table, $chunkMaxSize, &$array)
    {
        if (count($array) >= $chunkMaxSize) {
            $newArray = array_chunk($array, $chunkMaxSize);
            try {
                DB::beginTransaction();
                foreach ($newArray as $chunk) {
                    DB::table($table)->insert($chunk);
                }
                DB::commit();
            } catch (Exception $e) {
                dd($e);
            } catch (Throwable $e) {
                dd($e);
            }
        } else {
            DB::table($table)->insert($array);
        }
    }

    public function massiveInsertToCSVNonBlocking($table, $keys, &$values)
    {
        $this->massiveInsertToCSV($table, $keys, $values, false);
    }

    public function massiveInsertToCSV($table, $keys, &$values, $blocking = true)
    {
        echo "Total a importar: " . count($values) . " filas en la tabla $table\n";
        $filePath = getcwd() . "/$table.csv";
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $file = fopen($filePath, 'wa+');
        fputcsv($file, $keys);
        $i = 0;
        $increment = 20000;
        echo "RUTA: " . getcwd() . "\n";
        foreach ($values as $value) {
            $i++;
            fputcsv($file, $value);
            if ($i % $increment === 0) {
                echo "Importing to file : " . ($i - $increment) . " - " . $i . "\n";
            }
        }
        fclose($file);
        $seatKeysString = '';
        foreach ($keys as $seatKey) {
            $seatKeysString .= '"' . $seatKey . '",';
        }
        $seatKeysString = substr($seatKeysString, 0, -1);
        if ($blocking) {
            echo "Copia y pega el siguiente comando en una instancia de PGSQL conectada a la base de datos:\n";
            echo 'COPY "' . $table . '"(' . $seatKeysString . ') FROM \'' . $filePath . '\' DELIMITER \',\' CSV HEADER;' . "\n";
            echo "Cuando lo hayas hecho vuelve a lanzar el seeder desde donde lo dejaste (comentando las líneas)." . "\n";

            echo "Por favor, escribe (ok) para continuar: ";

            $handle = fopen("php://stdin", "r");
            while (trim(fgets($handle)) !== 'ok') {
                echo "Por favor, escribe (ok) para continuar: ";
            }
            fclose($handle);
        } else {
            echo 'COPY "' . $table . '"(' . $seatKeysString . ') FROM \'' . $filePath . '\' DELIMITER \',\' CSV HEADER;' . "\n";
        }
    }
}
