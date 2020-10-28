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
 * App\OrderReturn
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $orderReturnReasonId
 * @property string $pointOfSaleId
 * @property string $clientId
 * @property float $amount
 * @property string $mode
 * @property string|null $attemptDate
 * @property string|null $date
 * @property string $status
 * @property bool $returnDistribution
 * @property int|null $redsysNumber
 * @property string|null $authorizationCode
 * @property string|null $observations
 * @property-read OrderReturnReason $orderReturnReason
 * @property-read PointOfSale $pointOfSale
 * @property-read Collection|Ticket[] $tickets
 * @property-read int|null $ticketsCount
 * @method static SavitarBuilder|OrderReturn newModelQuery()
 * @method static SavitarBuilder|OrderReturn newQuery()
 * @method static SavitarBuilder|OrderReturn query()
 * @method static Builder|OrderReturn whereAmount($value)
 * @method static Builder|OrderReturn whereAttemptDate($value)
 * @method static Builder|OrderReturn whereAuthorizationCode($value)
 * @method static Builder|OrderReturn whereCreatedAt($value)
 * @method static Builder|OrderReturn whereCreatedBy($value)
 * @method static Builder|OrderReturn whereDate($value)
 * @method static Builder|OrderReturn whereDeletedAt($value)
 * @method static Builder|OrderReturn whereDeletedBy($value)
 * @method static Builder|OrderReturn whereId($value)
 * @method static Builder|OrderReturn whereMode($value)
 * @method static Builder|OrderReturn whereObservations($value)
 * @method static Builder|OrderReturn whereOrderReturnReasonId($value)
 * @method static Builder|OrderReturn wherePointOfSaleId($value)
 * @method static Builder|OrderReturn whereRedsysNumber($value)
 * @method static Builder|OrderReturn whereReturnDistribution($value)
 * @method static Builder|OrderReturn whereStatus($value)
 * @method static Builder|OrderReturn whereUpdatedAt($value)
 * @method static Builder|OrderReturn whereUpdatedBy($value)
 * @method static Builder|OrderReturn whereClientId($value)
 * @mixin Eloquent
 */
class OrderReturn extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'pointOfSaleName' => [
                'name' => 'Punto de venta',
                'type' => 'string',
                'sql' => 'PointOfSale.name',
                'foreignKey' => 'OrderReturn.pointOfSaleId',
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'mode' => [
                'name' => 'Modo',
                'type' => 'array',
                'sql' => 'OrderReturn.mode',
                'possibleValues' => ['tpv', 'transfer', 'cash'],
                'configuration' => [
                    'tpv' => ['html' => 'Retrocesión TPV', 'translation' => 'Retrocesión TPV'],
                    'transfer' => ['html' => 'Por transferencia', 'translation' => 'Por transferencia'],
                    'cash' => ['html' => 'Efectivo en PV', 'translation' => 'Efectivo en PV'],
                ],
                'default' => true,
            ],
            'orderReturnReasonName' => [
                'name' => 'Motivo',
                'type' => 'string',
                'sql' => 'OrderReturnReason.name',
                'foreignKey' => 'OrderReturn.orderReturnReasonId',
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'ticketsCount' => [
                'name' => 'Cantidad de entradas',
                'type' => 'count',
                'countRelation' => 'tickets',
                'default' => true,
            ],
            'amount' => [
                'name' => 'Importe',
                'type' => 'money',
                'sql' => 'OrderReturn.amount',
                'default' => true,
            ],
            'date' => [
                'name' => 'Fecha de devolución',
                'type' => 'fullDate',
                'sql' => 'OrderReturn.date',
                'default' => true,
            ],
            'status' => [
                'name' => 'Estado',
                'type' => 'array',
                'sql' => 'OrderReturn.status',
                'default' => true,
                'possibleValues' => ['successful', 'failed', 'attempt'],
                'configuration' => [
                    'attempt' => ['html' => 'En proceso', 'translation' => 'En proceso', 'statusColor' => 'orange-status'],
                    'successful' => ['html' => 'Exitoso', 'translation' => 'Exitoso', 'statusColor' => 'green-status'],
                    'failed' => ['html' => 'Fallido', 'translation' => 'Fallido', 'statusColor' => 'red-status'],
                ],
            ],
            'returnDistribution' => [
                'name' => 'Devolver g.distribución',
                'type' => 'boolean',
                'sql' => 'OrderReturn.returnDistribution',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'authorizationCode' => [
                'name' => 'Código de autorización',
                'type' => 'string',
                'sql' => 'OrderReturn.authorizationCode',
            ],
            'redsysNumber' => [
                'name' => 'Número de operación',
                'type' => 'string',
                'sql' => 'OrderReturn.redsysNumber',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'OrderReturn.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'OrderReturn.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'OrderReturn.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'OrderReturn.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'OrderReturn.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'orderReturnReason' => [
                'name' => 'Motivo',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => OrderReturnReason::class,
            ],
            'pointsOfSale' => [
                'name' => 'Taquillas',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => PointOfSale::class,
            ],
        ],
    ];

    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * An orderReturn has many tickets
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * An orderReturn belongs to an orderReturnReason.
     *
     * @return BelongsTo
     */
    public function orderReturnReason(): BelongsTo
    {
        return $this->belongsTo(OrderReturnReason::class)->withTrashed();
    }

    /**
     * An orderReturn belongs to a pointOfSale.
     *
     * @return BelongsTo
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class)->withTrashed();
    }

    /**
     * An orderReturn belongs to a client.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }
}
