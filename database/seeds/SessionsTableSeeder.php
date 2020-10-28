<?php

use App\Place;
use App\Session;
use App\Show;
use App\ShowTemplate;
use App\Space;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $places = Place::all();
        $shows = Show::all();
        $showTemplates = ShowTemplate::all();

        foreach ($shows as $key => $show) {
            $session = new Session();
            $place = $places->random(1)->first();
            // $place2 = $places->random(1)->first();
            /** @var ShowTemplate $showTemplate */
            /** @var ShowTemplate $showTemplate2 */
            $showTemplate = $showTemplates->get($key%count($showTemplates));
            // $showTemplate2 = $showTemplates->random(1)->first();

            /** @var Space $space */
            $space = $place->spaces()->get()->random(1)->first();
            $session->date = Carbon::now()->addMonths(random_int(1, 4))->addDays(random_int(0, 10));
            $session->status = 'A la venta';
            $session->show()->associate($show->id);
            $session->place()->associate($place->id);
            $session->space()->associate($space->id);
            $session->showTemplate()->associate($showTemplate);
            $session->vat = $showTemplate->showCategories()->first()->vat;
            $session->isActive = true;
            $session->save();

            if ($key % 2 > 0) {
                $session = new Session();
                $session->date = Carbon::now()->addMonths(random_int(5, 10))->addDays(random_int(0, 10));
                $session->status = 'A la venta';
                $session->show()->associate($show->id);
                $session->place()->associate($place->id);
                $session->space()->associate($space->id);
                $session->showTemplate()->associate($showTemplate);
                $session->vat = $showTemplate->showCategories()->first()->vat;
                $session->isActive = true;
                $session->save();
            }
        }
    }
}
