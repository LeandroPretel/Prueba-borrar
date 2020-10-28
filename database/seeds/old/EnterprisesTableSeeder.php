<?php

namespace old;

use App\Enterprise;
use Illuminate\Support\Str;
use Savitar\Auth\SavitarZone;

class EnterprisesTableSeeder extends OldTableSeeder
{
    protected $tableName = 'empresas';
    protected $defaultOrder = 'id_empresa';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        $enterprise = new Enterprise();
        $enterprise->name = $result->nombre;
        $enterprise->socialReason = $result->razon_social;
        $enterprise->nif = $result->cif;
        $enterprise->contactName = $result->nombre_contacto;
        $enterprise->contactNif = $result->nif_contacto;
        $enterprise->contactEmail = $result->email_contacto;
        $domicilio = $this->db->table('domicilios')->where('id_domicilio', $result->id_domicilio)->first();
        if ($domicilio) {
            if ($domicilio->npro === 'Illes Balears') {
                $domicilio->npro = 'Islas Baleares';
            }
            if ($domicilio->npro === 'Girona') {
                $domicilio->npro = 'Gerona';
            }
            if ($domicilio->npro === 'A Coruña') {
                $domicilio->npro = 'La Coruña';
            }
            $province = SavitarZone::whereSlug(Str::slug(Str::lower($domicilio->npro)))->first();
            if ($province) {
                $enterprise->province()->associate($province);
            }
            $country = SavitarZone::whereSlug('espana')->first();
            if ($country) {
                $enterprise->country()->associate($country);
            }
            $enterprise->city = $domicilio->dmun50;
            $enterprise->address = $domicilio->lineasimple;
        }
        $enterprise->zipCode = $result->cpos;
        $enterprise->chargeToAccount = $result->transferencia;
        $enterprise->chargeIban = $result->iban_cobros;
        $enterprise->paymentIban = $result->iban_pagos;
        $enterprise->email = $result->email_administracion;
        $enterprise->requireMinCommission = $result->requerir_minimo_comisiones;
        $enterprise->minCommission = $result->minimo_comisiones;
        $enterprise->oldId = $result->id_empresa;
        $enterprise->save();
    }
}
