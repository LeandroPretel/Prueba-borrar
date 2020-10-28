<?php

use Illuminate\Database\Seeder;

class SessionAreaFaresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /*
        $sessions = Session::all();

        $sessions->each(function (Session $session) {
            $space = $session->space()->first();
            $areaId1 = $space->areas->random(1)->first()->id;
            $areaId2 = $space->areas->random(1)->first()->id;
            $areasIds[] = $areaId1;
            if ($areaId2 !== $areaId1) {
                $areasIds[] = $areaId2;
            }
            foreach ($areasIds as $areaId) {
                $areaSession = new AreaSession();
                $areaSession->areaId = $areaId;
                $areaSession->sessionId = $session->id;
                $areaSession->save();
            }
        });
        */
    }
}
