<?php

namespace old;

use App\ShowCategory;
use App\ShowClassification;
use App\ShowTemplate;
use Savitar\Files\SavitarFile;

class ShowTemplatesTableSeeder extends OldTableSeeder
{
    protected $tableName = 'espectaculos';
    protected $defaultOrder = 'id_espectaculo';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        if (!$result->clasificacion) {
            $result->clasificacion = 'Todos los públicos';
        }
        $showClassification = ShowClassification::whereName($result->clasificacion)->first();
        if (!$showClassification) {
            $showClassification = new ShowClassification();
            $showClassification->name = $result->clasificacion;
            $showClassification->save();
        }
        $showTemplate = new ShowTemplate();
        $showTemplate->showClassification()->associate($showClassification);
        $showTemplate->name = $result->nombre;
        $showTemplate->webName = $result->nombre_web ?: $result->nombre;
        $showTemplate->ticketName = $result->nombre_entrada ?: $result->nombre;
        $showTemplate->slug = $result->linktext;
        $showTemplate->description = $result->descripcion;
        $showTemplate->duration = $result->duracion;
        $showTemplate->break = $result->duracion_descanso;
        $showTemplate->hasPassword = $result->requerir_password;
        if ($result->esp_password) {
            $showTemplate->password = bcrypt($result->esp_password);
        }
        $showTemplate->oldId = $result->id_espectaculo;
        $showTemplate->save();

        $showTemplate->showCategories()->sync(ShowCategory::where('oldId', $result->id_tipo_espectaculo)->first());

        $savitarFile = new SavitarFile();
        $savitarFile->name = $result->imagen;
        $savitarFile->url = 'https://www.redentradas.com/img/espectaculos/' . $result->imagen;
        $savitarFile->path = 'https://www.redentradas.com/img/espectaculos/' . $result->imagen;
        $savitarFile->extension = 'jpg';
        $savitarFile->category = 'mainImage';
        $showTemplate->files()->save($savitarFile);
    }
}
