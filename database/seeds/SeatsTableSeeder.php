<?php

use App\Seat;
use App\Sector;
use Illuminate\Database\Seeder;

class SeatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $sectors = Sector::whereIsNumbered(true)->get();
        foreach ($sectors as $sector) {
            $number = 1;
            for ($i = 1; $i < 6; $i++) {
                for ($j = 1; $j < 6; $j++) {
                    $seat = new Seat();
                    $seat->sector()->associate($sector);
                    $seat->row = $i;
                    $seat->column = $j;
                    $seat->rowName = $i;
                    $seat->number = $number;
                    $seat->status = 'enabled';
                    $seat->isForDisabled = false;
                    $seat->reducedVisibility = false;
                    $seat->save();
                    $number++;
                }
            }
            $sector->totalSeats = $number - 1;
            $sector->save();
        }
    }
}
