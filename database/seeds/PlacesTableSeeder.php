<?php

use App\Door;
use App\Place;
use Illuminate\Database\Seeder;
use Savitar\Auth\SavitarZone;
use Savitar\Files\SavitarFile;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $provinces = SavitarZone::all();

        $place = new Place();
        $place->name = 'Recinto primero';
        $place->webName = 'Recinto primero';
        $place->ticketName = 'Recinto primero';
        $place->provinceId = $provinces->random(1)->first()->id;
        $place->address = 'Calle del recinto nº 45';
        $place->city = 'Motril';
        $place->zipCode = '12345';
        $place->description = 'Has elit simul primis ne, regione minimum id cum. Sea deleniti dissentiet ea. Illud mollis moderatius ut per, at qui ubique populo. Eum ad cibo legimus, vim ei quidam fastidii. Quo debet vivendo ex. Qui ut admodum senserit partiendo';
        $place->hasAccessControl = true;
        $place->mapLink = "https://goo.gl/maps/KPqwF16EQxLjLHkFA";
        $place->save();

        $file = new SavitarFile();
        $file->category = 'mainImage';
        $file->name = 'Imagen.png';
        $file->size = 2400000;
        $file->path = '';
        $file->url = 'http://notedetengas.es/wp-content/uploads/2018/04/Vetusta-Morla-Salamanca.jpg';
        $file->extension = 'png';
        $file->pages = 1;
        $place->files()->save($file);

        $door = new Door();
        $door->name = 'Puerta principal';
        $door->webName = 'Puerta principal';
        $door->ticketName = 'Puerta principal';
        $door->place()->associate($place);
        $door->save();

        $place = new Place();
        $place->name = 'Recinto segundo';
        $place->webName = 'Recinto segundo';
        $place->ticketName = 'Recinto segundo';
        $place->provinceId = $provinces->random(1)->first()->id;
        $place->address = 'Calle ferial SN';
        $place->city = 'Villa Grande';
        $place->zipCode = '12345';
        $place->description = 'Has elit simul primis ne, regione minimum id cum. Sea deleniti dissentiet ea. Illud mollis moderatius ut per, at qui ubique populo. Eum ad cibo legimus, vim ei quidam fastidii. Quo debet vivendo ex. Qui ut admodum senserit partiendo';
        $place->hasAccessControl = false;
        $place->save();

        $file = new SavitarFile();
        $file->category = 'mainImage';
        $file->name = 'Imagen.png';
        $file->size = 2400000;
        $file->path = '';
        $file->url = 'https://www.menorca.info/sfAttachPlugin/336435.jpg';
        $file->extension = 'png';
        $file->pages = 1;
        $place->files()->save($file);

        $door = new Door();
        $door->name = 'Puerta Ronda';
        $door->webName = 'Puerta Ronda';
        $door->ticketName = 'Puerta Ronda';
        $door->place()->associate($place);
        $door->save();

        $place = new Place();
        $place->name = 'Recinto tercero';
        $place->webName = 'Recinto tercero';
        $place->ticketName = 'Recinto tercero';
        $place->provinceId = $provinces->random(1)->first()->id;
        $place->address = 'Avenida del río nº54';
        $place->city = 'Villa Abajo';
        $place->zipCode = '12345';
        $place->description = 'Has elit simul primis ne, regione minimum id cum. Sea deleniti dissentiet ea. Illud mollis moderatius ut per, at qui ubique populo. Eum ad cibo legimus, vim ei quidam fastidii. Quo debet vivendo ex. Qui ut admodum senserit partiendo';
        $place->hasAccessControl = false;
        $place->save();

        $file = new SavitarFile();
        $file->category = 'mainImage';
        $file->name = 'Imagen.png';
        $file->size = 2400000;
        $file->path = '';
        $file->url = 'https://thumbs.dreamstime.com/z/lugar-del-concierto-en-el-parque-municipal-la-provincia-m%C3%A1laga-andaluc%C3%ADa-espa%C3%B1a-de-marbella-104960807.jpg';
        $file->extension = 'png';
        $file->pages = 1;
        $place->files()->save($file);

        $door = new Door();
        $door->name = 'Puerta principal tercera';
        $door->webName = 'Puerta principal tercera';
        $door->ticketName = 'Puerta principal tercera';
        $door->place()->associate($place);
        $door->save();
    }
}
