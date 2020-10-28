<?php

use App\Area;
use App\Sector;
use Illuminate\Database\Seeder;

class SectorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $areas = Area::all();
        foreach ($areas as $key => $area) {
            $booleanArray = [true, false, true, true, false];

            $sector = new Sector();
            $sector->area()->associate($area->id);
            $sector->order = 1;
            $sector->name = 'Sector ' . ($key + 1);
            $sector->webName = 'Sector ' . ($key + 1);
            $sector->ticketName = 'Sector ' . ($key + 1);
            $sector->isNumbered = $booleanArray[array_rand(($booleanArray))];
            if (!$sector->isNumbered) {
                $sector->totalSeats = random_int(20, 100);
            }
            $sector->disabledAccess = true;
            $sector->reducedVisibility = false;
            $sector->stageLocation = 45;
            $sector->points = '[[[' . (83 + $key * 10) . ',' . (53 + $key * 10) . '], [' . (73 + $key * 10) . ', ' . (99 + $key * 10) . '], [' . (111 + $key * 10) . ', ' . (102 + $key * 10) . '], [' . (132 + $key * 10) . ', ' . (80 + $key * 10) . '], [' . (133 + $key * 10) . ', ' . (55 + $key * 10) . ']]]';
            $sector->centers = '[[' . (100 + $key * 10) . ',' . (75 + $key * 10) . ']]';
            $sector->save();
        }
    }
}
