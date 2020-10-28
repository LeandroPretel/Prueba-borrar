<?php

use App\Area;
use App\Space;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $spaces = Space::all();
        foreach ($spaces as $space) {
            $area = new Area();
            $area->order = 1;
            $area->name = 'Area 1';
            $area->webName = 'Area 1';
            $area->ticketName = 'Area 1';
            $area->color = '#FF0000';
            $area->space()->associate($space->id);
            $area->save();

            $area = new Area();
            $area->order = 2;
            $area->name = 'Area 2';
            $area->webName = 'Area 2';
            $area->ticketName = 'Area 2';
            $area->color = '#FFFF00';
            $area->space()->associate($space->id);
            $area->save();
        }
    }
}
