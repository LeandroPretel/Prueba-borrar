<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\OrderReturnReason
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $name
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read Collection|OrderReturn[] $orderReturns
 * @property-read int|null $orderReturnsCount
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|OrderReturnReason newModelQuery()
 * @method static SavitarBuilder|OrderReturnReason newQuery()
 * @method static SavitarBuilder|OrderReturnReason query()
 * @method static Builder|OrderReturnReason whereCreatedAt($value)
 * @method static Builder|OrderReturnReason whereCreatedBy($value)
 * @method static Builder|OrderReturnReason whereDeletedAt($value)
 * @method static Builder|OrderReturnReason whereDeletedBy($value)
 * @method static Builder|OrderReturnReason whereId($value)
 * @method static Builder|OrderReturnReason whereName($value)
 * @method static Builder|OrderReturnReason whereUpdatedAt($value)
 * @method static Builder|OrderReturnReason whereUpdatedBy($value)
 * @mixin Eloquent
 */
class OrderReturnReason extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'OrderReturnReason.name',
                'default' => true,
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'OrderReturnReason.createdAt',
                'default' => true,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'OrderReturnReason.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'OrderReturnReason.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'OrderReturnReason.updatedBy',
            ],
        ],
    ];

    /**
     * An orderReturnReason has many orderReturns
     *
     * @return HasMany
     */
    public function orderReturns(): HasMany
    {
        return $this->hasMany(OrderReturn::class);
    }
}
