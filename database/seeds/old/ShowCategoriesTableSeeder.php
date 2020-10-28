<?php

namespace old;

use App\ShowCategory;

class ShowCategoriesTableSeeder extends OldTableSeeder
{
    protected $tableName = 'tipos_espectaculo';
    protected $defaultOrder = 'id_tipo_espectaculo';

    private $vatTypes = [
        0 => 0, // EXENTO
        1 => 21, // GENERAL (21%)
        2 => 10, // REDUCIDO
        3 => 4, // SUPERREDUCIDO
        4 => 21, //TRANSICIONAL 21
    ];

    private $sgaeTypes = [
        1 => 10, // CONCIERTOS 10%
        2 => 0, // EXENTO
        3 => 10, // TEATRO 10%
        4 => 9, // TEATRO 9%
        5 => 1.5, // TEATRO 1,5%
        6 => 5.45, // VARIEDADES 5,45%
        7 => 3.5, // TEATRO 3,5%
    ];

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        $showCategory = new ShowCategory();
        $showCategory->name = $result->descripcion;
        $showCategory->vat = $this->vatTypes[$result->default_iva_tipo];
        $showCategory->sgaeFee = $this->sgaeTypes[$result->default_sgae_tipo];
        $showCategory->oldId = $result->id_tipo_espectaculo;
        $showCategory->save();
    }
}
