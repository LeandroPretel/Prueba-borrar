<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Discount
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property bool $isActive
 * @property string $name
 * @property string $code
 * @property string $type
 * @property bool $isPercentage
 * @property float $amount
 * @property float|null $minAmountToUse
 * @property float|null $maxAmountToUse
 * @property int $timesUsed
 * @property int|null $maximumUses
 * @property string|null $startDate
 * @property string|null $endDate
 * @property string|null $observations
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read Collection|Session[] $sessions
 * @property-read Collection|PointOfSale[] $pointsOfSale
 * @property-read Collection|Order[] $orders
 * @property-read int|null $sessionsCount
 * @property-read User|null $updatedByUser
 * @property-read int|null $ordersCount
 * @property-read int|null $pointsOfSaleCount
 * @method static SavitarBuilder|Discount newModelQuery()
 * @method static SavitarBuilder|Discount newQuery()
 * @method static SavitarBuilder|Discount query()
 * @method static Builder|Discount whereAmount($value)
 * @method static Builder|Discount whereCreatedAt($value)
 * @method static Builder|Discount whereCreatedBy($value)
 * @method static Builder|Discount whereDeletedAt($value)
 * @method static Builder|Discount whereDeletedBy($value)
 * @method static Builder|Discount whereEndDate($value)
 * @method static Builder|Discount whereId($value)
 * @method static Builder|Discount whereIsActive($value)
 * @method static Builder|Discount whereIsPercentage($value)
 * @method static Builder|Discount whereMaxAmountToUse($value)
 * @method static Builder|Discount whereMinAmountToUse($value)
 * @method static Builder|Discount whereName($value)
 * @method static Builder|Discount whereCode($value)
 * @method static Builder|Discount whereStartDate($value)
 * @method static Builder|Discount whereTimesUsed($value)
 * @method static Builder|Discount whereUpdatedAt($value)
 * @method static Builder|Discount whereUpdatedBy($value)
 * @method static Builder|Discount whereMaximumUses($value)
 * @method static Builder|Discount whereObservations($value)
 * @method static Builder|Discount whereType($value)
 * @mixin Eloquent
 */
class Discount extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'type' => [
                'name' => 'Tipo',
                'type' => 'array',
                'sql' => 'Discount.type',
                'possibleValues' => ['discount', 'promotion'],
                'configuration' => [
                    //, 'chipColor' => 'blue-chip'
                    'discount' => ['html' => 'Descuento', 'translation' => 'Descuento'],
                    'promotion' => ['html' => 'Promoción', 'translation' => 'Promoción'],
                ],
                'default' => true,
            ],
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Discount.name',
                'default' => true,
            ],
            'code' => [
                'name' => 'Código',
                'type' => 'string',
                'sql' => 'Discount.code',
                'default' => true,
            ],
            'amount' => [
                'name' => 'Cantidad',
                'type' => 'decimal',
                'sql' => 'Discount.amount',
                'default' => true,
            ],
            'isPercentage' => [
                'name' => 'Es porcentaje',
                'type' => 'boolean',
                'sql' => 'Discount.isPercentage',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
                'default' => true,
            ],
            'isActive' => [
                'name' => 'Estado',
                'type' => 'boolean',
                'sql' => 'Discount.isActive',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'Inactivo', 'translation' => 'Inactivo', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Activo', 'translation' => 'Activo', 'statusColor' => 'green-status'],
                ],
                'default' => true,
            ],
            'minAmountToUse' => [
                'name' => 'Cantidad mínima para uso',
                'type' => 'decimal',
                'sql' => 'Discount.minAmountToUse',
            ],
            'maxAmountToUse' => [
                'name' => 'Cantidad máxima para uso',
                'type' => 'decimal',
                'sql' => 'Discount.maxAmountToUse',
            ],
            'timesUsed' => [
                'name' => 'Nº de veces usado',
                'type' => 'int',
                'sql' => 'Discount.timesUsed',
            ],
            'maximumUses' => [
                'name' => 'Máximo de usos',
                'type' => 'int',
                'sql' => 'Discount.maximumUses',
            ],
            'startDate' => [
                'name' => 'Fecha de inicio',
                'type' => 'fullDate',
                'sql' => 'Discount.startDate',
                'save' => false,
                'update' => false,
            ],
            'endDate' => [
                'name' => 'Fecha de fin',
                'type' => 'fullDate',
                'sql' => 'Discount.endDate',
                'save' => false,
                'update' => false,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Discount.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Discount.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Discount.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Discount.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Discount.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'sessions' => [
                'name' => 'Sesiones',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Session::class,
            ],
            'pointsOfSale' => [
                'name' => 'Puntos de venta',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => PointOfSale::class,
            ],
        ],
    ];

    protected $hidden = [
        // 'createdBy',
        // 'updatedBy',
        // 'deletedAt',
        // 'deletedBy',
    ];

    /**
     * Get all of the orders that used this discount.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all of the sessions that are assigned this discount.
     */
    public function sessions(): MorphToMany
    {
        return $this->morphedByMany(Session::class, 'discountable');
    }

    /**
     * Get all of the pointsOfSale that are assigned this discount.
     */
    public function pointsOfSale(): MorphToMany
    {
        return $this->morphedByMany(PointOfSale::class, 'discountable');
    }
}
