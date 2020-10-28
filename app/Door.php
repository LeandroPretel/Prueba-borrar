<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Door
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $placeId
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string|null $observations
 * @property int|null $oldId
 * @property int $order
 * @property-read Place $place
 * @property-read Collection|Seat[] $seats
 * @property-read int|null $seatsCount
 * @property-read Collection|Sector[] $sectors
 * @property-read int|null $sectorsCount
 * @method static SavitarBuilder|Door newModelQuery()
 * @method static SavitarBuilder|Door newQuery()
 * @method static SavitarBuilder|Door query()
 * @method static Builder|Door whereCreatedAt($value)
 * @method static Builder|Door whereCreatedBy($value)
 * @method static Builder|Door whereDeletedAt($value)
 * @method static Builder|Door whereDeletedBy($value)
 * @method static Builder|Door whereId($value)
 * @method static Builder|Door whereName($value)
 * @method static Builder|Door whereObservations($value)
 * @method static Builder|Door whereOldId($value)
 * @method static Builder|Door wherePlaceId($value)
 * @method static Builder|Door whereTicketName($value)
 * @method static Builder|Door whereUpdatedAt($value)
 * @method static Builder|Door whereUpdatedBy($value)
 * @method static Builder|Door whereWebName($value)
 * @method static Builder|Door whereOrder($value)
 * @mixin Eloquent
 */
class Door extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Door.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'Door.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'Door.ticketName',
                'default' => true,
            ],
            'order' => [
                'name' => 'Orden',
                'type' => 'int',
                'sql' => 'Door.order',
                'default' => true,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Door.observations',
                'default' => true,
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Door.createdAt',
                'default' => true,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Door.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Door.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Door.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'place' => [
                'name' => 'Recinto',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Place::class,
            ],
            'sectors' => [
                'name' => 'Sectores',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Sector::class,
            ],
        ],
    ];

    /**
     * A door belongs to a place
     *
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * @return BelongsToMany
     */
    public function sectors(): BelongsToMany
    {
        return $this->belongsToMany(Sector::class)->using(DoorSector::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class)->using(DoorSeat::class)->withTimestamps();
    }
}
