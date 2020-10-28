<?php

namespace old;

use App\Enterprise;
use App\Show;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class ShowsTableSeeder extends OldTableSeeder
{
    protected $tableName = 'contratos';
    protected $defaultOrder = 'id_contrato';

    /**
     * @param $result
     * @throws Exception
     */
    public function createRecords($result): void
    {
        /** @var Enterprise $enterprise */
        $enterprise = Enterprise::where('oldId', $result->id_empresa_promotor)->first();
        if ($enterprise) {
            $account = $enterprise->accounts()->first();
            if ($account) {
                $show = new Show();
                $show->account()->associate($account);
                $show->name = $result->nombre;
                $show->webName = $result->nombre;
                $show->ticketName = $result->nombre;
                $show->createdAt = Carbon::parse($result->fecha_alta_utc);
                $show->oldId = $result->id_contrato;
                $show->id = Str::uuid();
                $show->saveWithoutEvents();
            }
        }
    }
}
