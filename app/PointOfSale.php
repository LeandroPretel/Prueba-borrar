<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Savitar\Auth\SavitarZone;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;
use Savitar\Models\Traits\UsesSlug;

/**
 * App\PointOfSale
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string|null $countryId
 * @property string|null $provinceId
 * @property bool $isWeb
 * @property bool $isAutomatic
 * @property bool $isAssisted
 * @property bool $isByPhone
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string $slug
 * @property bool $isActive
 * @property bool $isVisible
 * @property bool $isMaster
 * @property string|null $city
 * @property string|null $address
 * @property string|null $zipCode
 * @property string|null $phone
 * @property string|null $openingHours
 * @property bool $creditCardEnabled
 * @property bool $cashEnabled
 * @property bool $ticketPickUpEnabled
 * @property bool $serviceServedEnabled
 * @property bool $ticketSalesEnabled
 * @property bool $ticketSeasonsEnabled
 * @property bool $ticketVouchersEnabled
 * @property bool $reportsEnabled
 * @property bool $printTicketSeasonEnabled
 * @property bool $labelsEnabled
 * @property bool $printLabelsEnabled
 * @property bool $invitationsEnabled
 * @property bool $hardTicketEnabled
 * @property bool $homeTicketEnabled
 * @property string|null $clientHomeTicketId
 * @property bool $smsEnabled
 * @property string|null $smsUser
 * @property string|null $smsPassword
 * @property int|null $tpvCommerce
 * @property int|null $tpvTerminal
 * @property string|null $tpvKey
 * @property string|null $tpvPort
 * @property string|null $tpvVersion
 * @property string $liquidationPeriodicity
 * @property string|null $nextLiquidationEndDate
 * @property float|null $saleCommissionPercentage
 * @property float|null $saleCommissionMinimum
 * @property float|null $saleCommissionMaximum
 * @property float|null $shippingCommissionPercentage
 * @property float|null $shippingCommissionMinimum
 * @property float|null $shippingCommissionMaximum
 * @property float|null $printCommissionPercentage
 * @property float|null $printCommissionMinimum
 * @property float|null $printCommissionMaximum
 * @property string|null $observations
 * @property int|null $oldId
 * @property int|null $anfixCompanyAccountingAccountNumber
 * @property-read Collection|Account[] $accounts
 * @property-read int|null $accountsCount
 * @property-read SavitarZone|null $country
 * @property-read Collection|Discount[] $discounts
 * @property-read int|null $discountsCount
 * @property-read Collection|Enterprise[] $enterprises
 * @property-read int|null $enterprisesCount
 * @property-read Collection|Fare[] $fares
 * @property-read int|null $faresCount
 * @property-read Collection|OrderReturn[] $orderReturns
 * @property-read int|null $orderReturnsCount
 * @property-read Collection|Order[] $orders
 * @property-read int|null $ordersCount
 * @property-read Collection|Place[] $places
 * @property-read int|null $placesCount
 * @property-read SavitarZone|null $province
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @property-read Collection|TicketSeason[] $ticketSeasons
 * @property-read int|null $ticketSeasonsCount
 * @method static SavitarBuilder|PointOfSale newModelQuery()
 * @method static SavitarBuilder|PointOfSale newQuery()
 * @method static SavitarBuilder|PointOfSale query()
 * @method static Builder|PointOfSale whereAddress($value)
 * @method static Builder|PointOfSale whereCashEnabled($value)
 * @method static Builder|PointOfSale whereCity($value)
 * @method static Builder|PointOfSale whereClientHomeTicketId($value)
 * @method static Builder|PointOfSale whereCountryId($value)
 * @method static Builder|PointOfSale whereCreatedAt($value)
 * @method static Builder|PointOfSale whereCreatedBy($value)
 * @method static Builder|PointOfSale whereCreditCardEnabled($value)
 * @method static Builder|PointOfSale whereDeletedAt($value)
 * @method static Builder|PointOfSale whereDeletedBy($value)
 * @method static Builder|PointOfSale whereHardTicketEnabled($value)
 * @method static Builder|PointOfSale whereHomeTicketEnabled($value)
 * @method static Builder|PointOfSale whereId($value)
 * @method static Builder|PointOfSale whereInvitationsEnabled($value)
 * @method static Builder|PointOfSale whereIsActive($value)
 * @method static Builder|PointOfSale whereIsAssisted($value)
 * @method static Builder|PointOfSale whereIsAutomatic($value)
 * @method static Builder|PointOfSale whereIsByPhone($value)
 * @method static Builder|PointOfSale whereIsMaster($value)
 * @method static Builder|PointOfSale whereIsVisible($value)
 * @method static Builder|PointOfSale whereIsWeb($value)
 * @method static Builder|PointOfSale whereLabelsEnabled($value)
 * @method static Builder|PointOfSale whereLiquidationPeriodicity($value)
 * @method static Builder|PointOfSale whereName($value)
 * @method static Builder|PointOfSale whereNextLiquidationEndDate($value)
 * @method static Builder|PointOfSale whereObservations($value)
 * @method static Builder|PointOfSale whereOldId($value)
 * @method static Builder|PointOfSale whereOpeningHours($value)
 * @method static Builder|PointOfSale wherePhone($value)
 * @method static Builder|PointOfSale wherePrintLabelsEnabled($value)
 * @method static Builder|PointOfSale wherePrintTicketSeasonEnabled($value)
 * @method static Builder|PointOfSale whereProvinceId($value)
 * @method static Builder|PointOfSale whereReportsEnabled($value)
 * @method static Builder|PointOfSale whereSaleCommissionMaximum($value)
 * @method static Builder|PointOfSale whereSaleCommissionMinimum($value)
 * @method static Builder|PointOfSale whereSaleCommissionPercentage($value)
 * @method static Builder|PointOfSale whereServiceServedEnabled($value)
 * @method static Builder|PointOfSale whereShippingCommissionMaximum($value)
 * @method static Builder|PointOfSale whereShippingCommissionMinimum($value)
 * @method static Builder|PointOfSale whereShippingCommissionPercentage($value)
 * @method static Builder|PointOfSale wherePrintCommissionMaximum($value)
 * @method static Builder|PointOfSale wherePrintCommissionMinimum($value)
 * @method static Builder|PointOfSale wherePrintCommissionPercentage($value)
 * @method static Builder|PointOfSale whereSlug($value)
 * @method static Builder|PointOfSale whereSmsEnabled($value)
 * @method static Builder|PointOfSale whereSmsPassword($value)
 * @method static Builder|PointOfSale whereSmsUser($value)
 * @method static Builder|PointOfSale whereTicketName($value)
 * @method static Builder|PointOfSale whereTicketPickUpEnabled($value)
 * @method static Builder|PointOfSale whereTicketSalesEnabled($value)
 * @method static Builder|PointOfSale whereTicketSeasonsEnabled($value)
 * @method static Builder|PointOfSale whereTicketVouchersEnabled($value)
 * @method static Builder|PointOfSale whereTpvCommerce($value)
 * @method static Builder|PointOfSale whereTpvKey($value)
 * @method static Builder|PointOfSale whereTpvPort($value)
 * @method static Builder|PointOfSale whereTpvTerminal($value)
 * @method static Builder|PointOfSale whereTpvVersion($value)
 * @method static Builder|PointOfSale whereUpdatedAt($value)
 * @method static Builder|PointOfSale whereUpdatedBy($value)
 * @method static Builder|PointOfSale whereWebName($value)
 * @method static Builder|PointOfSale whereZipCode($value)
 * @method static Builder|PointOfSale whereAnfixCompanyAccountingAccountNumber($value)
 * @mixin Eloquent
 */
class PointOfSale extends SavitarModel
{
    use UsesSlug;

    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'PointOfSale.name',
                'default' => true,
            ],
            /*
            'status' => [
                'name' => 'Estado',
                'type' => 'array',
                'sql' => 'PointOfSale.status',
                'possibleValues' => ['active', 'inactive'],
                'configuration' => [
                    'inactive' => ['html' => 'Inactivo', 'translation' => 'Inactivo', 'statusColor' => 'red-status'],
                    'active' => ['html' => 'Activo', 'translation' => 'Activo', 'statusColor' => 'green-status'],
                ],
                'default' => true,
            ],
            */
            'isActive' => [
                'name' => 'Estado',
                'type' => 'boolean',
                'sql' => 'PointOfSale.isActive',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'Inactivo', 'translation' => 'Inactivo', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Activo', 'translation' => 'Activo', 'statusColor' => 'green-status'],
                ],
                'default' => true,
            ],
            'isVisible' => [
                'name' => 'Visible',
                'type' => 'boolean',
                'sql' => 'PointOfSale.isVisible',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
                'default' => true,
            ],
            'isMaster' => [
                'name' => 'Maestro',
                'type' => 'boolean',
                'sql' => 'PointOfSale.isMaster',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'isWeb' => [
                'name' => 'Web',
                'type' => 'boolean',
                'sql' => 'PointOfSale.isWeb',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'isAutomatic' => [
                'name' => 'Automático',
                'type' => 'boolean',
                'sql' => 'PointOfSale.isAutomatic',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'isAssisted' => [
                'name' => 'Asistido',
                'type' => 'boolean',
                'sql' => 'PointOfSale.isAssisted',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'isByPhone' => [
                'name' => 'Telefónico',
                'type' => 'boolean',
                'sql' => 'PointOfSale.isByPhone',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'provinceName' => [
                'name' => 'Provincia',
                'type' => 'string',
                'sql' => 'Zone.name',
                'foreignKey' => 'PointOfSale.provinceId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'city' => [
                'name' => 'Localidad',
                'type' => 'string',
                'sql' => 'PointOfSale.city',
            ],
            'address' => [
                'name' => 'Dirección',
                'type' => 'string',
                'sql' => 'PointOfSale.address',
                'default' => true,
            ],
            'zipCode' => [
                'name' => 'Código Postal',
                'type' => 'string',
                'sql' => 'PointOfSale.zipCode',
            ],
            'phone' => [
                'name' => 'Teléfono',
                'type' => 'string',
                'sql' => 'PointOfSale.phone',
            ],
            'openingHours' => [
                'name' => 'Horario',
                'type' => 'string',
                'sql' => 'PointOfSale.openingHours',
            ],
            'creditCardEnabled' => [
                'name' => 'Pago con tarjeta',
                'type' => 'boolean',
                'sql' => 'PointOfSale.creditCardEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'cashEnabled' => [
                'name' => 'Pago en efectivo',
                'type' => 'boolean',
                'sql' => 'PointOfSale.cashEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'ticketPickUpEnabled' => [
                'name' => 'Recogida de entradas',
                'type' => 'boolean',
                'sql' => 'PointOfSale.ticketPickUpEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'serviceServedEnabled' => [
                'name' => 'Servicio atendido',
                'type' => 'boolean',
                'sql' => 'PointOfSale.serviceServedEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'ticketSalesEnabled' => [
                'name' => 'Venta de entradas',
                'type' => 'boolean',
                'sql' => 'PointOfSale.ticketSalesEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'ticketSeasonsEnabled' => [
                'name' => 'Venta de abonos',
                'type' => 'boolean',
                'sql' => 'PointOfSale.ticketSeasonsEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'ticketVouchersEnabled' => [
                'name' => 'Venta de bonos',
                'type' => 'boolean',
                'sql' => 'PointOfSale.ticketVouchersEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'reportsEnabled' => [
                'name' => 'Informes',
                'type' => 'boolean',
                'sql' => 'PointOfSale.reportsEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'printTicketSeasonEnabled' => [
                'name' => 'Impresión de abonos',
                'type' => 'boolean',
                'sql' => 'PointOfSale.printTicketSeasonEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'labelsEnabled' => [
                'name' => 'Etiquetas de venta',
                'type' => 'boolean',
                'sql' => 'PointOfSale.labelsEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'printLabelsEnabled' => [
                'name' => 'Impresión de etiquetas',
                'type' => 'boolean',
                'sql' => 'PointOfSale.printLabelsEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'invitationsEnabled' => [
                'name' => 'Invitaciones',
                'type' => 'boolean',
                'sql' => 'PointOfSale.invitationsEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'hardTicketEnabled' => [
                'name' => 'Hard Ticket',
                'type' => 'boolean',
                'sql' => 'PointOfSale.hardTicketEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'homeTicketEnabled' => [
                'name' => 'Home Ticket',
                'type' => 'boolean',
                'sql' => 'PointOfSale.homeTicketEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'smsEnabled' => [
                'name' => 'SMS',
                'type' => 'boolean',
                'sql' => 'PointOfSale.smsEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'smsUser' => [
                'name' => 'Usuario SMS',
                'type' => 'string',
                'sql' => 'PointOfSale.smsUser',
            ],
            'tpvCommerce' => [
                'name' => 'TVP Comercio',
                'type' => 'int',
                'sql' => 'PointOfSale.tpvCommerce',
            ],
            'tpvTerminal' => [
                'name' => 'TVP Terminal',
                'type' => 'int',
                'sql' => 'PointOfSale.tpvTerminal',
            ],
            'tpvKey' => [
                'name' => 'TVP Clave',
                'type' => 'string',
                'sql' => 'PointOfSale.tpvKey',
            ],
            'tpvPort' => [
                'name' => 'TVP Puerto',
                'type' => 'string',
                'sql' => 'PointOfSale.tpvPort',
            ],
            'tpvVersion' => [
                'name' => 'TVP Versión',
                'type' => 'string',
                'sql' => 'PointOfSale.tpvVersion',
            ],
            'liquidationPeriodicity' => [
                'name' => 'Periodicidad liquidación',
                'type' => 'array',
                'sql' => 'PointOfSale.liquidationPeriodicity',
                'possibleValues' => ['annual', 'biannual', 'quarterly', 'monthly'],
                'configuration' => [
                    'annual' => ['html' => 'Anual', 'translation' => 'Anual'],
                    'biannual' => ['html' => 'Semestral', 'translation' => 'Semestral'],
                    'quarterly' => ['html' => 'Trimestral', 'translation' => 'Trimestral'],
                    'monthly' => ['html' => 'Mensual', 'translation' => 'Mensual'],
                ],
            ],
            'nextLiquidationEndDate' => [
                'name' => 'Fecha finalización del periodo liquidación',
                'type' => 'fullDate',
                'sql' => 'PointOfSale.nextLiquidationEndDate',
            ],
            'saleCommissionPercentage' => [
                'name' => 'Comisión base de venta (%)',
                'type' => 'decimal',
                'sql' => 'PointOfSale.saleCommissionPercentage',
            ],
            'saleCommissionMinimum' => [
                'name' => 'Comisión mínima de venta',
                'type' => 'money',
                'sql' => 'PointOfSale.saleCommissionMinimum',
            ],
            'saleCommissionMaximum' => [
                'name' => 'Comisión máxima de venta',
                'type' => 'money',
                'sql' => 'PointOfSale.saleCommissionMaximum',
            ],
            'shippingCommissionPercentage' => [
                'name' => 'Comisión base de envío (%)',
                'type' => 'decimal',
                'sql' => 'PointOfSale.shippingCommissionPercentage',
            ],
            'shippingCommissionMinimum' => [
                'name' => 'Comisión mínima de envío',
                'type' => 'money',
                'sql' => 'PointOfSale.shippingCommissionMinimum',
            ],
            'shippingCommissionMaximum' => [
                'name' => 'Comisión máxima de envío',
                'type' => 'money',
                'sql' => 'PointOfSale.shippingCommissionMaximum',
            ],
            'printCommissionPercentage' => [
                'name' => 'Comisión base de impresión (%)',
                'type' => 'decimal',
                'sql' => 'PointOfSale.printCommissionPercentage',
            ],
            'printCommissionMinimum' => [
                'name' => 'Comisión mínima de impresión',
                'type' => 'money',
                'sql' => 'PointOfSale.printCommissionMinimum',
            ],
            'printCommissionMaximum' => [
                'name' => 'Comisión máxima de impresión',
                'type' => 'money',
                'sql' => 'PointOfSale.printCommissionMaximum',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'PointOfSale.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'PointOfSale.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'PointOfSale.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'PointOfSale.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'PointOfSale.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'users' => [
                'name' => 'Usuario',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => User::class,
            ],
            'country' => [
                'name' => 'País',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SavitarZone::class,
            ],
            'province' => [
                'name' => 'Provincia',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SavitarZone::class,
            ],
            'accounts' => [
                'name' => 'Cuentas',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Account::class,
            ],
            'sessions' => [
                'name' => 'Sesiones',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Session::class,
            ],
            'places' => [
                'name' => 'Recintos en los que es taquilla',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Place::class,
            ],
            'orders' => [
                'name' => 'Compras',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Order::class,
            ],
            'orderReturns' => [
                'name' => 'Devoluciones',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => OrderReturn::class,
            ],
            'smsPassword' => [
                'name' => 'Clave SMS',
                'type' => 'string',
                'sql' => 'PointOfSale.smsPassword',
            ],
            'clientHomeTicketId' => [
                'name' => 'Cliente HomeTicket',
                'type' => 'string',
                'sql' => 'PointOfSale.clientHomeTicketId',
            ],
        ],
    ];

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(SavitarZone::class, 'countryId');
    }

    /**
     * @return BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(SavitarZone::class, 'provinceId');
    }

    /**
     * A ticket office belongs to many sessions
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class)->using(PointOfSaleSession::class)->withTimestamps();
    }

    /**
     * A ticket office belongs to many accounts (sponsors)
     *
     * @return BelongsToMany
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class)->using(AccountPointOfSale::class)->withTimestamps();
    }

    /**
     * A PointOfSale belongsToMany fares
     *
     * @return BelongsToMany
     */
    public function fares(): BelongsToMany
    {
        return $this->belongsToMany(Fare::class)->using(FarePointOfSale::class)->withPivot([
            'maximumTicketsToSell',
            'createdAt',
            'updatedAt',
        ]);
    }

    /**
     * A ticket office belongs to many places
     *
     * @return BelongsToMany
     */
    public function places(): BelongsToMany
    {
        return $this->belongsToMany(Place::class)->using(PlacePointOfSale::class)->withTimestamps();
    }

    /**
     * Get all of the discounts for the pointOfSale.
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(Discount::class, 'discountable');
    }

    /**
     * Get all of the orders for the pointOfSale.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all of the orderReturns for the pointOfSale.
     */
    public function orderReturns(): HasMany
    {
        return $this->hasMany(OrderReturn::class);
    }

    /**
     * A ticketSeason belongsToMany pointOfSale
     *
     * @return BelongsToMany
     */
    public function ticketSeasons(): BelongsToMany
    {
        return $this->belongsToMany(TicketSeason::class, 'TicketSeasonPointOfSale')
            ->using(TicketSeasonPointOfSale::class)
            ->withPivot([
                'createdAt',
                'updatedAt',
            ]);
    }

    /**
     * Get all of the enterprises for the point of sale.
     */
    public function enterprises(): MorphToMany
    {
        return $this->morphToMany(Enterprise::class, 'enterprisable', 'Enterprisable');
    }
}
