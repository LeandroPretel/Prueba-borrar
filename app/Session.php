<?php

namespace App;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Savitar\Files\SavitarFile;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Session
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $showId
 * @property string $placeId
 * @property string $spaceId
 * @property string $showTemplateId
 * @property bool $isActive
 * @property string $status
 * @property bool $isHidden
 * @property Carbon $date
 * @property bool $displayAsSoon
 * @property bool $advanceSaleEnabled
 * @property string|null $advanceSaleStartDate
 * @property string|null $advanceSaleFinishDate
 * @property string|null $assistedSellEndDate
 * @property bool $ticketOfficesEnabled
 * @property string|null $ticketOfficesStartDate
 * @property string|null $ticketOfficesEndDate
 * @property bool $pickUpInPointsOfSaleEnabled
 * @property string|null $pickUpInPointsOfSaleStartDate
 * @property string|null $pickUpInPointsOfSaleEndDate
 * @property string|null $openingDoorsDate
 * @property string|null $closingDoorsDate
 * @property float $vat
 * @property bool $isLiquidated
 * @property float|null $automaticDistributionPercentage
 * @property float|null $automaticDistributionMinimum
 * @property float|null $automaticDistributionMaximum
 * @property float|null $pointOfSaleCommissionPercentage
 * @property float|null $pointOfSaleCommissionMinimum
 * @property float|null $pointOfSaleCommissionMaximum
 * @property float|null $printCommissionPercentage
 * @property float|null $printCommissionMinimum
 * @property float|null $printCommissionMaximum
 * @property bool $isExternal
 * @property string|null $externalUrl
 * @property string|null $externalEnterpriseId
 * @property bool $returnExpensesWhenCancelled
 * @property string|null $observations
 * @property string|null $defaultFareId
 * @property int|null $oldId
 * @property-read Collection|Access[] $accesses
 * @property-read int|null $accessesCount
 * @property-read Collection|SessionSeat[] $availableSessionSeats
 * @property-read int|null $availableSessionSeatsCount
 * @property-read Fare|null $defaultFare
 * @property-read Collection|Discount[] $discounts
 * @property-read int|null $discountsCount
 * @property-read Collection|Fare[] $fares
 * @property-read int|null $faresCount
 * @property-read Collection|SessionSeat[] $freeSessionSeats
 * @property-read int|null $freeSessionSeatsCount
 * @property-read mixed|string $mainImageUrl
 * @property-read Collection|SessionSeat[] $hardTicketSessionSeats
 * @property-read int|null $hardTicketSessionSeatsCount
 * @property-read Collection|SessionSeat[] $isForDisabledSessionSeats
 * @property-read int|null $isForDisabledSessionSeatsCount
 * @property-read Collection|Partner[] $partners
 * @property-read int|null $partnersCount
 * @property-read Place $place
 * @property-read Collection|PointOfSale[] $pointsOfSale
 * @property-read int|null $pointsOfSaleCount
 * @property-read Collection|SessionArea[] $sessionAreas
 * @property-read int|null $sessionAreasCount
 * @property-read Collection|SessionDoor[] $sessionDoors
 * @property-read int|null $sessionDoorsCount
 * @property-read SessionPrintModel|null $sessionPrintModel
 * @property-read Collection|SessionSeat[] $sessionSeats
 * @property-read int|null $sessionSeatsCount
 * @property-read Collection|SessionSector[] $sessionSectors
 * @property-read int|null $sessionSectorsCount
 * @property-read Show $show
 * @property-read Enterprise $externalEnterprise
 * @property-read ShowTemplate $showTemplate
 * @property-read Collection|SessionSeat[] $soldSessionSeats
 * @property-read int|null $soldSessionSeatsCount
 * @property-read Space $space
 * @property-read Collection|TicketSeasonOrder[] $ticketSeasonOrders
 * @property-read int|null $ticketSeasonOrdersCount
 * @property-read Collection|TicketSeason[] $ticketSeasons
 * @property-read int|null $ticketSeasonsCount
 * @property-read Collection|TicketVoucherOrder[] $ticketVoucherOrders
 * @property-read int|null $ticketVoucherOrdersCount
 * @property-read Collection|TicketVoucher[] $ticketVouchers
 * @property-read int|null $ticketVouchersCount
 * @property-read Collection|Ticket[] $tickets
 * @property-read int|null $ticketsCount
 * @method static SavitarBuilder|Session newModelQuery()
 * @method static SavitarBuilder|Session newQuery()
 * @method static SavitarBuilder|Session query()
 * @method static Builder|Session whereAdvanceSaleEnabled($value)
 * @method static Builder|Session whereAdvanceSaleFinishDate($value)
 * @method static Builder|Session whereAdvanceSaleStartDate($value)
 * @method static Builder|Session whereAssistedSellEndDate($value)
 * @method static Builder|Session whereAutomaticDistributionMaximum($value)
 * @method static Builder|Session whereAutomaticDistributionMinimum($value)
 * @method static Builder|Session whereAutomaticDistributionPercentage($value)
 * @method static Builder|Session whereClosingDoorsDate($value)
 * @method static Builder|Session whereCreatedAt($value)
 * @method static Builder|Session whereCreatedBy($value)
 * @method static Builder|Session whereDate($value)
 * @method static Builder|Session whereDefaultFareId($value)
 * @method static Builder|Session whereDeletedAt($value)
 * @method static Builder|Session whereDeletedBy($value)
 * @method static Builder|Session whereDisplayAsSoon($value)
 * @method static Builder|Session whereExternalEnterpriseId($value)
 * @method static Builder|Session whereExternalUrl($value)
 * @method static Builder|Session whereId($value)
 * @method static Builder|Session whereIsActive($value)
 * @method static Builder|Session whereIsExternal($value)
 * @method static Builder|Session whereIsHidden($value)
 * @method static Builder|Session whereIsLiquidated($value)
 * @method static Builder|Session whereObservations($value)
 * @method static Builder|Session whereOldId($value)
 * @method static Builder|Session whereOpeningDoorsDate($value)
 * @method static Builder|Session wherePickUpInPointsOfSaleEnabled($value)
 * @method static Builder|Session wherePickUpInPointsOfSaleEndDate($value)
 * @method static Builder|Session wherePickUpInPointsOfSaleStartDate($value)
 * @method static Builder|Session wherePlaceId($value)
 * @method static Builder|Session wherePointOfSaleCommissionMaximum($value)
 * @method static Builder|Session wherePointOfSaleCommissionMinimum($value)
 * @method static Builder|Session wherePointOfSaleCommissionPercentage($value)
 * @method static Builder|Session wherePrintCommissionMaximum($value)
 * @method static Builder|Session wherePrintCommissionMinimum($value)
 * @method static Builder|Session wherePrintCommissionPercentage($value)
 * @method static Builder|Session whereReturnExpensesWhenCancelled($value)
 * @method static Builder|Session whereShowId($value)
 * @method static Builder|Session whereShowTemplateId($value)
 * @method static Builder|Session whereSpaceId($value)
 * @method static Builder|Session whereStatus($value)
 * @method static Builder|Session whereTicketOfficesEnabled($value)
 * @method static Builder|Session whereTicketOfficesEndDate($value)
 * @method static Builder|Session whereTicketOfficesStartDate($value)
 * @method static Builder|Session whereUpdatedAt($value)
 * @method static Builder|Session whereUpdatedBy($value)
 * @method static Builder|Session whereVat($value)
 * @mixin Eloquent
 * @property float|null $hardTicketPrintCommissionPercentage
 * @property float|null $hardTicketPrintCommissionMinimum
 * @property float|null $hardTicketPrintCommissionMaximum
 * @property float|null $invitationCommissionPercentage
 * @property float|null $invitationCommissionMinimum
 * @property float|null $invitationCommissionMaximum
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereHardTicketPrintCommissionMaximum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereHardTicketPrintCommissionMinimum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereHardTicketPrintCommissionPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereInvitationCommissionMaximum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereInvitationCommissionMinimum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Session whereInvitationCommissionPercentage($value)
 */
class Session extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'mainImageUrl' => [
                'name' => 'Imagen',
                'type' => 'icon',
                'sql' => 'mainImageUrl',
                'notSortable' => true,
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'showTemplateName' => [
                'name' => 'Espectáculo',
                'type' => 'string',
                'sql' => 'ShowTemplate.name',
                'foreignKey' => 'Session.showTemplateId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'Session.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'Session.ticketName',
                'default' => true,
            ],
            'date' => [
                'name' => 'Fecha',
                'type' => 'fullDate',
                'sql' => 'Session.date',
                'save' => false,
                'update' => false,
                'default' => true,
            ],
            'placeName' => [
                'name' => 'Recinto',
                'type' => 'string',
                'sql' => 'Place.name',
                'foreignKey' => 'Session.placeId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'spaceName' => [
                'name' => 'Aforo',
                'type' => 'string',
                'sql' => 'Space.name',
                'foreignKey' => 'Session.spaceId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'isActive' => [
                'name' => 'Activa',
                'type' => 'boolean',
                'sql' => 'Session.isActive',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'Inactiva', 'translation' => 'Inactiva', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Activa', 'translation' => 'Activa', 'statusColor' => 'green-status'],
                ],
                'default' => true,
            ],
            'status' => [
                'name' => 'Estado',
                'type' => 'array',
                'sql' => 'Session.status',
                'possibleValues' => ['A la venta', 'Agotada', 'Finalizada'],
                'configuration' => [
                    'A la venta' => ['html' => 'A la venta', 'translation' => 'A la venta'],
                    'Agotada' => ['html' => 'Agotada', 'translation' => 'Agotada'],
                    'Finalizada' => ['html' => 'Finalizada', 'translation' => 'Finalizada'],
                ],
                'default' => true,
            ],
            'isHidden' => [
                'name' => 'Visibilidad',
                'type' => 'boolean',
                'sql' => 'Session.isHidden',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'Visible', 'translation' => 'Visible', 'statusColor' => 'green-status'],
                    'true' => ['html' => 'Oculta', 'translation' => 'Oculta', 'statusColor' => 'red-status'],
                ],
                'default' => true,
            ],
            'strictDoorAccess' => [
                'name' => 'Restringir puertas',
                'type' => 'boolean',
                'sql' => 'Session.strictDoorAccess',
                'possibleValues' => [false, true],
                'default' => true,
            ],
            /*
            'soldSessionSeatsCount' => [
                'name' => 'Vendidas',
                'type' => 'count',
                'countRelation' => 'soldSessionSeats',
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'freeSessionSeatsCount' => [
                'name' => 'Disponibles',
                'type' => 'count',
                'countRelation' => 'freeSessionSeats',
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            */
            'displayAsSoon' => [
                'name' => 'Mostrar como próximamente',
                'type' => 'boolean',
                'sql' => 'Session.displayAsSoon',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'advanceSaleEnabled' => [
                'name' => 'Venta anticipada',
                'type' => 'boolean',
                'sql' => 'Session.advanceSaleEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'advanceSaleStartDate' => [
                'name' => 'Fecha inicio de venta anticipada',
                'type' => 'fullDate',
                'sql' => 'Session.advanceSaleStartDate',
                'save' => false,
                'update' => false,
            ],
            'advanceSaleFinishDate' => [
                'name' => 'Fecha cierre de venta anticipada',
                'type' => 'fullDate',
                'sql' => 'Session.advanceSaleFinishDate',
                'save' => false,
                'update' => false,
            ],
            'assistedSellEndDate' => [
                'name' => 'Fecha cierre venta en kioskos asistidos',
                'type' => 'fullDate',
                'sql' => 'Session.assistedSellEndDate',
                'save' => false,
                'update' => false,
            ],
            'ticketOfficesEnabled' => [
                'name' => 'Taquillas',
                'type' => 'boolean',
                'sql' => 'Session.ticketOfficesEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'ticketOfficesStartDate' => [
                'name' => 'Fecha apertura de taquillas',
                'type' => 'fullDate',
                'sql' => 'Session.ticketOfficesStartDate',
                'save' => false,
                'update' => false,
            ],
            'ticketOfficesEndDate' => [
                'name' => 'Fecha cierre de taquillas',
                'type' => 'fullDate',
                'sql' => 'Session.ticketOfficesEndDate',
                'save' => false,
                'update' => false,
            ],
            'pickUpInPointsOfSaleEnabled' => [
                'name' => 'Recogida en puntos de venta',
                'type' => 'boolean',
                'sql' => 'Session.pickUpInPointsOfSaleEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'pickUpInPointsOfSaleStartDate' => [
                'name' => 'Fecha inicio recogida de entradas',
                'type' => 'fullDate',
                'sql' => 'Session.pickUpInPointsOfSaleStartDate',
                'save' => false,
                'update' => false,
            ],
            'pickUpInPointsOfSaleEndDate' => [
                'name' => 'Fecha cierre de recogida de entradas',
                'type' => 'fullDate',
                'sql' => 'Session.pickUpInPointsOfSaleEndDate',
                'save' => false,
                'update' => false,
            ],
            'openingDoorsDate' => [
                'name' => 'Fecha de apertura de puertas',
                'type' => 'fullDate',
                'sql' => 'Session.openingDoorsDate',
                'save' => false,
                'update' => false,
            ],
            'closingDoorsDate' => [
                'name' => 'Fecha de cierre de puertas',
                'type' => 'fullDate',
                'sql' => 'Session.closingDoorsDate',
                'save' => false,
                'update' => false,
            ],
            'isLiquidated' => [
                'name' => 'Liquidado',
                'type' => 'boolean',
                'sql' => 'Session.isLiquidated',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'automaticDistributionPercentage' => [
                'name' => 'Base de gastos de distribución (%)',
                'type' => 'decimal',
                'sql' => 'Session.automaticDistributionPercentage',
            ],
            'automaticDistributionMinimum' => [
                'name' => 'Mínimo de gastos de distribución',
                'type' => 'money',
                'sql' => 'Session.automaticDistributionMinimum',
            ],
            'automaticDistributionMaximum' => [
                'name' => 'Máximo de gastos de distribución',
                'type' => 'money',
                'sql' => 'Session.automaticDistributionMaximum',
            ],
            'pointOfSaleCommissionPercentage' => [
                'name' => 'Comisión base de punto de venta (%)',
                'type' => 'decimal',
                'sql' => 'Session.pointOfSaleCommissionPercentage',
            ],
            'pointOfSaleCommissionMinimum' => [
                'name' => 'Comisión mínima de punto de venta',
                'type' => 'money',
                'sql' => 'Session.pointOfSaleCommissionMinimum',
            ],
            'pointOfSaleCommissionMaximum' => [
                'name' => 'Comisión máxima de punto de venta',
                'type' => 'money',
                'sql' => 'Session.pointOfSaleCommissionMaximum',
            ],
            'printCommissionPercentage' => [
                'name' => 'Comisión base de impresión (%)',
                'type' => 'decimal',
                'sql' => 'Session.printCommissionPercentage',
            ],
            'printCommissionMinimum' => [
                'name' => 'Comisión mínima de impresión',
                'type' => 'money',
                'sql' => 'Session.printCommissionMinimum',
            ],
            'printCommissionMaximum' => [
                'name' => 'Comisión máxima de impresión',
                'type' => 'money',
                'sql' => 'Session.printCommissionMaximum',
            ],
            'hardTicketPrintCommissionPercentage' => [
                'name' => 'Comisión de impresión de hard ticket(%)',
                'type' => 'decimal',
                'sql' => 'Session.hardTicketPrintCommissionPercentage',
            ],
            'hardTicketPrintCommissionMinimum' => [
                'name' => 'Comisión mínima de impresión de hard ticket',
                'type' => 'money',
                'sql' => 'Session.hardTicketPrintCommissionMinimum',
            ],
            'hardTicketPrintCommissionMaximum' => [
                'name' => 'Comisión máxima de impresión de hard ticket',
                'type' => 'money',
                'sql' => 'Session.hardTicketPrintCommissionMaximum',
            ],
            'invitationCommissionPercentage' => [
                'name' => 'Comisión base de invitación (%)',
                'type' => 'decimal',
                'sql' => 'Session.invitationCommissionPercentage',
            ],
            'invitationCommissionMinimum' => [
                'name' => 'Comisión mínima de invitación',
                'type' => 'money',
                'sql' => 'Session.invitationCommissionMinimum',
            ],
            'invitationCommissionMaximum' => [
                'name' => 'Comisión máxima de invitación',
                'type' => 'money',
                'sql' => 'Session.invitationCommissionMaximum',
            ],
            'vat' => [
                'name' => 'Tipo de IVA',
                'type' => 'array',
                'sql' => 'Session.vat',
                'possibleValues' => [0.00, 4.00, 8.00, 18.00],
                'configuration' => [
                    '0.00' => ['html' => 'EXENTO (0%)', 'translation' => 'EXENTO (0%)'],
                    '4.00' => ['html' => 'SUPERREDUCIDO (4%)', 'translation' => 'SUPERREDUCIDO (4%)'],
                    '8.00' => ['html' => 'REDUCIDO (8%)', 'translation' => 'REDUCIDO (8%)'],
                    '18.00' => ['html' => 'GENERAL (18%)', 'translation' => 'GENERAL (18%)'],
                ],
            ],
            'returnExpensesWhenCancelled' => [
                'name' => 'Devolución de gastos si cancelado',
                'type' => 'boolean',
                'sql' => 'Session.returnExpensesWhenCancelled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'isExternal' => [
                'name' => 'Evento externo',
                'type' => 'boolean',
                'sql' => 'Session.isExternal',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'externalUrl' => [
                'name' => 'URL de evento externo',
                'type' => 'string',
                'sql' => 'Session.externalUrl',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Session.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Session.createdAt',
                'default' => true,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Session.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Session.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Session.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'showTemplate' => [
                'name' => 'Espectáculo',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => ShowTemplate::class,
            ],
            'place' => [
                'name' => 'Recinto',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Place::class,
            ],
            'space' => [
                'name' => 'Aforo',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Space::class,
            ],
            'pointsOfSale' => [
                'name' => 'Puntos de venta',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => PointOfSale::class,
            ],
            /*
            'partners' => [
                'name' => 'Partners',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Partner::class,
            ],
            */
            'tickets' => [
                'name' => 'Entradas',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Ticket::class,
            ],
            'accesses' => [
                'name' => 'Accesos',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Access::class,
            ],
            'defaultFare' => [
                'name' => 'Tarifa predeterminada',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Fare::class,
            ],
            'sessionPrintModel' => [
                'name' => 'Modelo impresión entrada',
                'type' => 'relation',
                'relationType' => 'hasOne',
                'relatedModelClass' => SessionPrintModel::class,
            ],
            'externalEnterprise' => [
                'name' => 'Empresa externa',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Enterprise::class,
            ],
        ],
    ];

    /**
     * @var array
     */
    protected $appends = ['mainImageUrl'];
    /**
     * @var array
     */
    protected $dates = ['date'];

    /**
     * @return mixed|string
     * @throws Exception
     */
    public function getMainImageUrlAttribute()
    {
        $photo = SavitarFile::whereFileableId($this->showTemplateId)->where('category', 'mainImage')->first();
        return $photo ? $photo->url : 'assets/event-placeholder.svg';
    }

    /**
     * A session belongs to a place
     *
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class)->withTrashed();
    }

    /**
     * A session belongs to a space
     *
     * @return BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class)->withTrashed();
    }

    /**
     * A session belongs to a show
     *
     * @return BelongsTo
     */
    public function show(): BelongsTo
    {
        return $this->belongsTo(Show::class)->withTrashed();
    }

    /**
     * A session belongs to a show template
     *
     * @return BelongsTo
     */
    public function showTemplate(): BelongsTo
    {
        return $this->belongsTo(ShowTemplate::class)->withTrashed()
            ->with([
                'files',
                'showCategories',
                'showClassification'
            ])->select(['id', 'name', 'webName', 'ticketName', 'description', 'duration', 'break', 'additionalInfo']);
    }

    /**
     * A session belongsToMany pointsOfSale
     *
     * @return BelongsToMany
     */
    public function pointsOfSale(): BelongsToMany
    {
        return $this->belongsToMany(PointOfSale::class)->using(PointOfSaleSession::class)->withTimestamps();
    }

    /**
     * A session has many fares
     *
     * @return HasMany
     */
    public function fares(): HasMany
    {
        return $this->hasMany(Fare::class)->orderBy('createdAt');
    }

    /**
     * A session has one default fare
     *
     * @return BelongsTo
     */
    public function defaultFare(): BelongsTo
    {
        return $this->belongsTo(Fare::class);
    }

    /**
     * @return HasMany
     */
    public function sessionDoors(): HasMany
    {
        return $this->hasMany(SessionDoor::class)
            ->orderBy('order');
    }

    /**
     * @return HasMany
     */
    public function sessionAreas(): HasMany
    {
        return $this->hasMany(SessionArea::class)
            ->orderBy('order');
    }

    /**
     * @return HasMany
     */
    public function sessionSectors(): HasMany
    {
        return $this->hasMany(SessionSector::class)
            ->orderBy('order');
    }

    /**
     * A session has many sessionSeats
     *
     * @return HasMany
     */
    public function sessionSeats(): HasMany
    {
        return $this->hasMany(SessionSeat::class)
            ->orderBy('row', 'asc')
            ->orderBy('column', 'asc');
    }

    /**
     * A session has many available sessionSeats
     *
     * @return HasMany
     */
    public function availableSessionSeats(): HasMany
    {
        return $this->hasMany(SessionSeat::class)
            ->where('status', '<>', 'disabled')
            ->where('status', '<>', 'locked')
            ->where('status', '<>', 'deleted')
            ->orderBy('row', 'asc')
            ->orderBy('column', 'asc');
    }

    /**
     * A session has many sessionSeats
     *
     * @return HasMany
     */
    public function soldSessionSeats(): HasMany
    {
        return $this->hasMany(SessionSeat::class)
            ->where(static function (Builder $builder) {
                $builder->where('status', 'sold')
                    ->orWhere('status', 'hard-ticket');
            })
            ->orderBy('row', 'asc')
            ->orderBy('column', 'asc');
    }

    /**
     * A session has many sessionSeats in 'enabled' status
     *
     * @return HasMany
     */
    public function freeSessionSeats(): HasMany
    {
        return $this->hasMany(SessionSeat::class)
            ->where('status', 'enabled')
            ->orderBy('row', 'asc')
            ->orderBy('column', 'asc');
    }

    /**
     * A session has many sessionSeats in 'hard-ticket' status
     *
     * @return HasMany
     */
    public function hardTicketSessionSeats(): HasMany
    {
        return $this->hasMany(SessionSeat::class)
            ->where('status', 'hard-ticket')
            ->orderBy('row', 'asc')
            ->orderBy('column', 'asc');
    }

    /**
     * A session has many sessionSeats in 'hard-ticket' status
     *
     * @return HasMany
     */
    public function isForDisabledSessionSeats(): HasMany
    {
        return $this->hasMany(SessionSeat::class)
            ->where('isForDisabled', true)
            ->orderBy('row', 'asc')
            ->orderBy('column', 'asc');
    }

    /**
     * A session has many tickets
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * A session has many accesses
     *
     * @return HasMany
     */
    public function accesses(): HasMany
    {
        return $this->hasMany(Access::class);
    }

    /**
     * Get all of the discounts for the session.
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(Discount::class, 'discountable');
    }

    /**
     * A session has many 'successful' accesses
     *
     */
    public function successfulAccessesCheckCount(): int
    {
        $accesses = $this->hasMany(Access::class)
            ->orderBy('createdAt', 'desc')
            ->where('status', '<>', 'error')
            ->select(['clientId', 'status'])
            ->get();

        /*
        $outsOrErrors = $this->hasMany(Access::class)
            ->where('status', 'error')
            ->orWhere('status', 'out')
            ->orderBy('createdAt', 'desc')
            ->groupBy(['clientId', 'createdAt'])->count();
        */
        $accesses = $accesses->unique('clientId')->filter(static function ($value) {
            return $value['status'] === 'successful';
        });
        return $accesses->count();
    }

    /**
     * A ticketSeason belongs to many sessions
     *
     * @return BelongsToMany
     */
    public function ticketSeasons(): BelongsToMany
    {
        return $this->belongsToMany(TicketSeason::class, 'TicketSeasonSession')
            ->using(TicketSeasonSession::class)
            ->withTimestamps();
    }

    /**
     * A ticketSeasonOrder belongs to many sessions
     *
     * @return BelongsToMany
     */
    public function ticketSeasonOrders(): BelongsToMany
    {
        return $this->belongsToMany(TicketSeasonOrder::class, 'TicketSeasonOrderSession')
            ->using(TicketSeasonOrderSession::class)
            ->withTimestamps();
    }

    /**
     * A ticketSeason belongs to many sessions
     *
     * @return BelongsToMany
     */
    public function ticketVouchers(): BelongsToMany
    {
        return $this->belongsToMany(TicketVoucher::class, 'TicketVoucherSession')
            ->using(TicketVoucherSession::class)
            ->withTimestamps();
    }

    /**
     * A ticketVoucherOrder belongs to many sessions
     *
     * @return BelongsToMany
     */
    public function ticketVoucherOrders(): BelongsToMany
    {
        return $this->belongsToMany(TicketVoucherOrder::class, 'TicketVoucherOrderSession')
            ->using(TicketVoucherOrderSession::class)
            ->withTimestamps();
    }

    /**
     * A session has one sessionPrintModel
     *
     * @return HasOne
     */
    public function sessionPrintModel(): HasOne
    {
        return $this->hasOne(SessionPrintModel::class);
    }

    /**
     * @return BelongsToMany
     */
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'PartnerSession')
            ->using(PartnerSession::class)
            ->withPivot([
                'createdAt',
                'updatedAt',
                'commissionPercentage',
                'commissionMinimum',
                'commissionMaximum',
            ]);
    }

    /**
     * @return BelongsTo
     */
    public function externalEnterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class, 'externalEnterpriseId');
    }
}
