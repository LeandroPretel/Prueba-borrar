<?php

namespace old;

use App\Enterprise;
use App\Partner;
use Carbon\Carbon;

class PartnersTableSeeder extends OldTableSeeder
{
    protected $tableName = 'partners';
    protected $defaultOrder = 'id_partner';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        /** @var Enterprise $enterprise */
        $enterprise = Enterprise::where('oldId', $result->id_empresa)->first();
        if ($enterprise) {
            $partner = new Partner();
            $partner->name = $enterprise->name;
            $partner->isActive = $result->activo;
            $partner->liquidationUntilDate = Carbon::parse($result->periodo_liquidacion_hasta);
            $commission = $this->db->table('tramos_comision_partners')->where('id_partner', $result->id_partner)->first();
            if ($commission) {
                $partner->commissionPercentage = $commission->porcentaje;
                $partner->commissionMinimum = $commission->minimo;
                $partner->commissionMaximum = $commission->maximo;
            }
            $partner->oldId = $result->id_partner;
            $partner->save();

            $partner->enterprises()->sync($enterprise);
        }
    }
}
