<?php

namespace old;

use App\Place;
use Illuminate\Support\Str;
use Savitar\Auth\SavitarZone;
use Savitar\Files\SavitarFile;

class PlacesTableSeeder extends OldTableSeeder
{
    protected $tableName = 'recintos';
    protected $defaultOrder = 'id_recinto';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        $place = new Place();
        $place->name = $result->nombre;
        $place->webName = $result->nombre_web ?: $result->nombre;
        $place->ticketName = $result->nombre_entrada ?: $result->nombre;
        $place->description = $result->descripcion;
        $place->bannerText = $result->descripcion;
        $place->bannerTextIsVisible = $result->mostrar_nombre;
        $place->bannerTextColor = $result->color_nombre;
        $place->hasAccessControl = $result->control_acceso;
        $domicilio = $this->db->table('domicilios')->where('id_domicilio', $result->id_domicilio)->first();
        if ($domicilio) {
            $province = SavitarZone::whereSlug(Str::slug(Str::lower($domicilio->npro)))->first();
            if ($province) {
                $place->province()->associate($province);
            }
            $country = SavitarZone::whereSlug('espana')->first();
            if ($country) {
                $place->country()->associate($country);
            }
            $place->city = $domicilio->dmun50;
            $place->address = $domicilio->lineasimple;
        }
        $place->zipCode = $result->cpos;
        $place->oldId = $result->id_recinto;
        $place->save();

        $savitarFile = new SavitarFile();
        $savitarFile->name = $result->imagen;
        $savitarFile->url = 'https://www.redentradas.com/img/recintos/' . $result->imagen;
        $savitarFile->path = 'https://www.redentradas.com/img/recintos/' . $result->imagen;
        $savitarFile->extension = 'jpg';
        $savitarFile->category = 'mainImage';
        $place->files()->save($savitarFile);
    }
}
