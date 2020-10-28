<?php

namespace old;

use App\Enterprise;
use App\PointOfSale;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Savitar\Auth\SavitarZone;

class PointsOfSaleTableSeeder extends OldTableSeeder
{
    protected $tableName = 'puntos_venta';
    protected $defaultOrder = 'id_punto_venta';

    /**
     * @param $result
     */
    public function createRecords($result): void
    {
        /** @var Enterprise $enterprise */
        $enterprise = Enterprise::where('oldId', $result->id_empresa)->first();
        $pointOfSaleType = $this->db->table('tipos_puntos_venta')->where('id_tipo_punto_venta', $result->id_tipo_punto_venta)->first();
        if ($enterprise && $pointOfSaleType) {
            $pointOfSale = new PointOfSale();
            switch ($pointOfSaleType->nemonico) {
                case 'WEB':
                    $pointOfSale->isWeb = true;
                    break;
                case 'AUT':
                    $pointOfSale->isAutomatic = true;
                    break;
                case 'POS':
                    $pointOfSale->isAssisted = true;
                    break;
                case 'TEL':
                    $pointOfSale->isByPhone = true;
                    break;
            }
            $pointOfSale->name = $result->nombre;
            $pointOfSale->webName = $result->nombre_web ?: $result->nombre;
            $pointOfSale->ticketName = $result->nombre_web ?: $result->nombre;
            $pointOfSale->isActive = $result->activo;
            $pointOfSale->isVisible = $result->visible;
            $pointOfSale->isMaster = $result->es_maestro;
            $pointOfSale->openingHours = $result->horario;
            $domicilio = $this->db->table('domicilios')->where('id_domicilio', $result->id_domicilio)->first();
            if ($domicilio) {
                $province = SavitarZone::whereSlug(Str::slug(Str::lower($domicilio->npro)))->first();
                if ($province) {
                    $pointOfSale->province()->associate($province);
                }
                $country = SavitarZone::whereSlug('espana')->first();
                if ($country) {
                    $pointOfSale->country()->associate($country);
                }
                $pointOfSale->city = $domicilio->dmun50;
                $pointOfSale->address = $domicilio->lineasimple;
            }
            $pointOfSale->zipCode = $result->cpos;
            // Métodos de pago
            $pointOfSale->creditCardEnabled = $result->tarjeta;
            $pointOfSale->cashEnabled = $result->efectivo;
            // Servicios
            $pointOfSale->ticketPickUpEnabled = $result->recogida;
            $pointOfSale->serviceServedEnabled = false;
            $pointOfSale->ticketSalesEnabled = true;
            $pointOfSale->ticketSeasonsEnabled = $result->abonos;
            $pointOfSale->ticketVouchersEnabled = false;
            $pointOfSale->reportsEnabled = $result->informes;
            $pointOfSale->printTicketSeasonEnabled = $result->imprimir_tarjeta;
            $pointOfSale->labelsEnabled = $result->etiquetar_compra;
            $pointOfSale->printLabelsEnabled = $result->imprimir_etiqueta;
            $pointOfSale->invitationsEnabled = false;
            $pointOfSale->hardTicketEnabled = false;
            $pointOfSale->homeTicketEnabled = $result->invitaciones_hometicket;
            $pointOfSale->clientHomeTicketId = $result->id_cliente_hometicket;
            // SMS
            if ($result->enviar_sms) {
                $pointOfSale->smsEnabled = $result->enviar_sms;
                $pointOfSale->smsUser = $result->sms_user;
                $pointOfSale->smsPassword = bcrypt($result->sms_pass);
            }
            // TPV
            $pointOfSale->tpvCommerce = $result->tpcpc_comercio;
            $pointOfSale->tpvTerminal = $result->tpvpc_terminal;
            $pointOfSale->tpvKey = $result->tpvpc_clave;
            $pointOfSale->tpvPort = $result->tpvpc_puerto;
            $pointOfSale->tpvVersion = $result->tpvpc_version;

            // Comisiones y liquidaciones
            $pointOfSale->liquidationPeriodicity = 'annual';
            $pointOfSale->nextLiquidationEndDate = Carbon::parse($result->periodo_liquidacion_hasta);

            $pointOfSale->shippingCommissionPercentage = $result->comision_envios_porcentaje;
            $pointOfSale->shippingCommissionMinimum = $result->comision_envios_minimo;
            $pointOfSale->shippingCommissionMaximum = $result->comision_envios_maximo;
            $commission = $this->db->table('tramos_comision_venta_puntos')->where('id_punto_venta', $result->id_punto_venta)->first();
            if ($commission) {
                $pointOfSale->saleCommissionPercentage = $commission->porcentaje;
                $pointOfSale->saleCommissionMinimum = $commission->minimo;
                $pointOfSale->saleCommissionMaximum = $commission->maximo;
            }
            $printCommission = $this->db->table('tramos_comision_impresion_puntos')->where('id_punto_venta', $result->id_punto_venta)->first();
            if ($printCommission) {
                $pointOfSale->printCommissionPercentage = $commission->porcentaje;
                $pointOfSale->printCommissionMinimum = $commission->minimo;
                $pointOfSale->printCommissionMaximum = $commission->maximo;
            }
            $pointOfSale->oldId = $result->id_punto_venta;
            $pointOfSale->save();
            $pointOfSale->enterprises()->sync($enterprise);
            // WIP Usuarios
            /*
            config(['savitar_auth.confirm_mail_enabled_aux' => false]);
            $user = SavitarUser::where('email', $result->fact_email)->firstOrNew([]);
            // $user->account()->associate($account);
            $user->role()->associate(SavitarRole::where('slug', 'punto-de-venta')->first());
            $user->email = $enterprise->email;
            $user->name = $result->nombre_entrada;
            //$user->password = bcrypt($result->clave);
            $user->password = $result->clave;
            $user->emailConfirmed = true;
            $user->save();
            config(['savitar_auth.confirm_mail_enabled_aux' => true]);
            */
        }
    }
}
