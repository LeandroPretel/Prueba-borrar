<?php

namespace old;

use App\Area;
use App\Sector;
use Illuminate\Support\Str;
use Savitar\Models\SavitarBuilder;

class SectorsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'zonas';
    protected $defaultOrder = 'id_zona';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        $area = Area::where('oldId', $result->id_area)
            ->whereHas('space', static function (SavitarBuilder $query) use ($result) {
                $query->where('oldId', '=', $result->id_aforo);
            })
            ->first();
        if ($area) {
            $sector = new Sector();
            $sector->area()->associate($area);
            $sector->name = $result->nombre;
            $sector->webName = $result->nombre_web ?: $result->nombre;
            $sector->ticketName = $result->nombre_entrada ?: $result->nombre;
            $sector->isNumbered = $result->numerada;
            $sector->disabledAccess = $result->accesible_discapacitados;
            $sector->reducedVisibility = $result->visibilidad_reducida;
            $sector->rows = $result->alto;
            $sector->columns = $result->ancho;
            $sector->totalSeats = $result->plazasenventa;
            $sector->order = $result->orden;
            // $sector->observations = $result->orden;
            // 400 ours, 500 him
            // $sector->points = '[[[' . (83 + $key * 10) . ',' . (53 + $key * 10) . '], [' . (73 + $key * 10) . ', ' . (99 + $key * 10) . '], [' . (111 + $key * 10) . ', ' . (102 + $key * 10) . '], [' . (132 + $key * 10) . ', ' . (80 + $key * 10) . '], [' . (133 + $key * 10) . ', ' . (55 + $key * 10) . ']]]';
            // $sector->centers = '[[' . (100 + $key * 10) . ',' . (75 + $key * 10) . ']]';
            if ($result->coord_x && $result->coord_y) {
                $sector->centers = '[[' . $result->coord_x * 4 / 5 . ',' . $result->coord_y * 4 / 5 . ']]';
            } else {
                $sector->centers = '[]';
            }
            // situacion = stageLocation
            if ($result->mapcoords) {
                $sector->points = '[[';
                $points = explode(',', $result->mapcoords);
                $size = count($points);
                for ($i = 0; $i < $size; $i += 2) {
                    if ($points[$i] && $points[$i + 1]) {
                        $sector->points .= '[' . $points[$i] * 4 / 5 . ',' . $points[$i + 1] * 4 / 5 . ']';
                        if ($i + 2 !== $size) {
                            $sector->points .= ',';
                        }
                    }
                }
                $sector->points .= ']]';
            } else {
                $sector->points = '[]';
            }

            $sector->oldId = $result->id_zona;
            $sector->id = Str::uuid();
            $sector->saveWithoutEvents();
        }
    }
}
