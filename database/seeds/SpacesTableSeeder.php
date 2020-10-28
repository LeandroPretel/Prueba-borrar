<?php

use App\Place;
use App\Space;
use Illuminate\Database\Seeder;
use Savitar\Files\SavitarFile;

class SpacesTableSeeder extends Seeder
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

        foreach ($places as $place) {
            $space = new Space();
            $space->name = 'Aforo 1';
            $space->webName = 'Aforo 1';
            $space->ticketName = 'Aforo 1';
            $space->denomination = 'Escenario';
            $space->maximumCapacity = random_int(200, 1000);
            $space->place()->associate($place);
            $space->save();

            $file = new SavitarFile();
            $file->category = 'mainImage';
            $file->name = 'Aforo1.jpg';
            $file->size = 2400000;
            $file->path = '';
            $file->url = 'https://www.imagevienna.com/uploads/tx_dmfgalleria/saalplan-reduced-slider_06.jpg';
            $file->extension = 'jpg';
            $file->pages = 1;
            $space->files()->save($file);

            $space = new Space();
            $space->name = 'Aforo 2';
            $space->webName = 'Aforo 2';
            $space->ticketName = 'Aforo 2';
            $space->denomination = 'Escenario';
            $space->maximumCapacity = random_int(200, 1000);
            $space->place()->associate($place);
            $space->save();

            $file = new SavitarFile();
            $file->category = 'mainImage';
            $file->name = 'Aforo2.jpg';
            $file->size = 2400000;
            $file->path = '';
            $file->url = 'https://motorpointarenacardiff.co.uk/sites/default/files/9037_cma_website_-_phase_2_seating_plan_v3-01.jpg';
            $file->extension = 'jpg';
            $file->pages = 1;
            $space->files()->save($file);

            $space = new Space();
            $space->name = 'Aforo 3';
            $space->webName = 'Aforo 3';
            $space->ticketName = 'Aforo 3';
            $space->denomination = 'Escenario';
            $space->maximumCapacity = random_int(200, 1000);
            $space->place()->associate($place);
            $space->save();

            $file = new SavitarFile();
            $file->category = 'mainImage';
            $file->name = 'Aforo3.png';
            $file->size = 2400000;
            $file->path = '';
            $file->url = 'https://koobin-media.s3.amazonaws.com/media/auditoribcn/aforos/sala-1L.png';
            $file->extension = 'png';
            $file->pages = 1;
            $space->files()->save($file);

            // $places->random(1)->first()
            $space = new Space();
            $space->name = 'Aforo 4';
            $space->webName = 'Aforo 4';
            $space->ticketName = 'Aforo 4';
            $space->denomination = 'Escenario';
            $space->maximumCapacity = random_int(200, 1000);
            $space->place()->associate($place);
            $space->save();

            $file = new SavitarFile();
            $file->category = 'mainImage';
            $file->name = 'Aforo4.jpg';
            $file->size = 2400000;
            $file->path = '';
            $file->url = 'https://www.proexa.es/wp-content/uploads/MAPA-ZONAS-REUBICACION-SM.jpg';
            $file->extension = 'jpg';
            $file->pages = 1;
            $space->files()->save($file);

            $space = new Space();
            $space->name = 'Aforo 5';
            $space->webName = 'Aforo 5';
            $space->ticketName = 'Aforo 5';
            $space->denomination = 'Escenario';
            $space->maximumCapacity = random_int(200, 1000);
            $space->place()->associate($place);
            $space->save();

            $file = new SavitarFile();
            $file->category = 'mainImage';
            $file->name = 'Aforo5.jpg';
            $file->size = 2400000;
            $file->path = '';
            $file->url = 'https://www.tupalacio.org/wp-content/uploads/2017/08/FullSizeRender.jpg';
            $file->extension = 'jpg';
            $file->pages = 1;
            $space->files()->save($file);
        }

    }
}
