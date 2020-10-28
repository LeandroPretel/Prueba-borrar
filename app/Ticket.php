<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Ticket
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $sessionId
 * @property string $orderId
 * @property string|null $sessionSeatId
 * @property string|null $sessionAreaFareId
 * @property string|null $orderReturnId
 * @property string $locator
 * @property int|null $number
 * @property float $baseAmount
 * @property float $baseAmountWithDiscount
 * @property float $distributionAmount
 * @property float $distributionAmountWithDiscount
 * @property float $amount
 * @property float $amountWithDiscount
 * @property string|null $anfixAccountingEntryId
 * @property string|null $returnAnfixAccountingEntryId
 * @property-read Collection|Access[] $accesses
 * @property-read int|null $accessesCount
 * @property-read Order $order
 * @property-read OrderReturn|null $orderReturn
 * @property-read Session $session
 * @property-read SessionAreaFare|null $sessionAreaFare
 * @property-read SessionSeat|null $sessionSeat
 * @method static SavitarBuilder|Ticket newModelQuery()
 * @method static SavitarBuilder|Ticket newQuery()
 * @method static SavitarBuilder|Ticket query()
 * @method static Builder|Ticket whereAmount($value)
 * @method static Builder|Ticket whereAmountWithDiscount($value)
 * @method static Builder|Ticket whereAnfixAccountingEntryId($value)
 * @method static Builder|Ticket whereBaseAmount($value)
 * @method static Builder|Ticket whereBaseAmountWithDiscount($value)
 * @method static Builder|Ticket whereCreatedAt($value)
 * @method static Builder|Ticket whereCreatedBy($value)
 * @method static Builder|Ticket whereDeletedAt($value)
 * @method static Builder|Ticket whereDeletedBy($value)
 * @method static Builder|Ticket whereDistributionAmount($value)
 * @method static Builder|Ticket whereDistributionAmountWithDiscount($value)
 * @method static Builder|Ticket whereId($value)
 * @method static Builder|Ticket whereLocator($value)
 * @method static Builder|Ticket whereNumber($value)
 * @method static Builder|Ticket whereOrderId($value)
 * @method static Builder|Ticket whereOrderReturnId($value)
 * @method static Builder|Ticket whereSessionAreaFareId($value)
 * @method static Builder|Ticket whereSessionId($value)
 * @method static Builder|Ticket whereSessionSeatId($value)
 * @method static Builder|Ticket whereUpdatedAt($value)
 * @method static Builder|Ticket whereUpdatedBy($value)
 * @method static Builder|Ticket whereReturnAnfixAccountingEntryId($value)
 * @mixin Eloquent
 */
class Ticket extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'locator' => [
                'name' => 'Localizador',
                'type' => 'string',
                'sql' => 'Ticket.locator',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'number' => [
                'name' => 'Número',
                'type' => 'int',
                'sql' => 'Ticket.number',
                'update' => false,
                'save' => false,
            ],
            'sessionSeatRow' => [
                'name' => 'Fila',
                'type' => 'string',
                'sql' => 'SessionSeat.row',
                'foreignKey' => 'Ticket.sessionSeatId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'sessionSeatNumber' => [
                'name' => 'Butaca',
                'type' => 'string',
                'sql' => 'SessionSeat.number',
                'foreignKey' => 'Ticket.sessionSeatId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'sessionSectorName' => [
                'name' => 'Sector',
                'type' => 'string',
                'sql' => 'SessionSector.name',
                'foreignKey' => 'SessionSeat.sessionSectorId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'sessionAreaName' => [
                'name' => 'Área',
                'type' => 'string',
                'sql' => 'SessionArea.name',
                'foreignKey' => 'SessionSector.sessionAreaId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'baseAmount' => [
                'name' => 'Precio base sin descuento',
                'type' => 'money',
                'sql' => 'Ticket.baseAmount',
            ],
            'baseAmountWithDiscount' => [
                'name' => 'Precio base',
                'type' => 'money',
                'sql' => 'Ticket.baseAmountWithDiscount',
                'default' => true,
            ],
            'distributionAmount' => [
                'name' => 'Gastos distribución sin descuento',
                'type' => 'money',
                'sql' => 'Ticket.distributionAmount',
            ],
            'distributionAmountWithDiscount' => [
                'name' => 'Gastos distribución',
                'type' => 'money',
                'sql' => 'Ticket.distributionAmountWithDiscount',
                'default' => true,
            ],
            'amount' => [
                'name' => 'Precio total sin descuento',
                'type' => 'money',
                'sql' => 'Ticket.amount',
            ],
            'amountWithDiscount' => [
                'name' => 'Precio total',
                'type' => 'money',
                'sql' => 'Ticket.amountWithDiscount',
                'default' => true,
            ],
            /*
            'status' => [
                'name' => 'Estado (Control de acceso)',
                'type' => 'array',
                'sql' => 'Ticket.status',
                'possibleValues' => ['pending', 'used'],
                'configuration' => [
                    'pending' => ['html' => 'Pendiente', 'translation' => 'Pendiente', 'statusColor' => 'orange-status'],
                    'used' => ['html' => 'Usada', 'translation' => 'Usada', 'statusColor' => 'green-status'],
                ],
            ],
            'entryDate' => [
                'name' => 'Fecha de entrada',
                'type' => 'fullDate',
                'sql' => 'Ticket.entryDate',
            ],
            */
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Ticket.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Ticket.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Ticket.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Ticket.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'session' => [
                'name' => 'Sesión',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Session::class,
            ],
            'order' => [
                'name' => 'Compra',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Order::class,
            ],
            'orderReturn' => [
                'name' => 'Devolución',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => OrderReturn::class,
            ],
            'sessionSeat' => [
                'name' => 'Localidad',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SessionSeat::class,
            ],
            'sessionAreaFare' => [
                'name' => 'Tarifa área',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SessionAreaFare::class,
            ],
            'accesses' => [
                'name' => 'Accesos',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Access::class,
            ],
        ],
    ];

    /**
     * A ticket belongs to a session.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * A ticket belongs to an order.
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * A ticket may belongs to an order return.
     *
     * @return BelongsTo
     */
    public function orderReturn(): BelongsTo
    {
        return $this->belongsTo(OrderReturn::class);
    }

    /**
     * A ticket belongs to a sessionSeat.
     *
     * @return BelongsTo
     */
    public function sessionSeat(): BelongsTo
    {
        return $this->belongsTo(SessionSeat::class);
    }

    /**
     * A ticket belongsTo a sessionAreaFare.
     *
     * @return BelongsTo
     */
    public function sessionAreaFare(): BelongsTo
    {
        return $this->belongsTo(SessionAreaFare::class);
    }

    /**
     * A ticket has many accesses.
     *
     * @return HasMany
     */
    public function accesses(): HasMany
    {
        return $this->hasMany(Access::class);
    }
}
