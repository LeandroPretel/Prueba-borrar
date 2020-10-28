<?php

namespace old;

use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AddIdsTableSeeder extends Seeder
{
    private $tables = [
        // Clientes
        'User',
        'Client',
        // Base
        'Enterprise',
        'Partner',
        'Account',
        'PointOfSale',
        // Recintos
        'Place',
        'Space',
        'Door',
        'Area',
        'Sector',
        // 'Seat',
        // Shows
        'TicketSeasonGroup',
        'TicketSeason',
        'Artist',
        'ShowCategory',
        'ShowTemplate',
        'Show',
        'Session',
        'SessionArea',
        'SessionSector',
        'SessionDoor',
        'SessionSeat',
        'Fare',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        echo "\e[0;33mImporting:\e[0m Fake ids\n";
        $startTime = microtime(true);
        $count = 0;
        try {
            foreach ($this->tables as $table) {
                if (!Schema::hasColumn($table, 'oldId')) {
                    Schema::table($table, static function (Blueprint $table) {
                        $table->integer('oldId')->index()->nullable();
                    });
                }
                $count++;
            }

            $runTime = round(microtime(true) - $startTime, 2);
            echo "\e[0;32mCreated:\e[0m {$count} id columns ({$runTime} seconds)\n";
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            echo "\e[0;31mError:\e[0m Fake id's import is not available\n";
        }
    }
}
