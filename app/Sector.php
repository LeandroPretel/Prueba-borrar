<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Sector
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $areaId
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property bool $isNumbered
 * @property int $totalSeats
 * @property int $order
 * @property int|null $rows
 * @property int|null $columns
 * @property bool $disabledAccess
 * @property bool $reducedVisibility
 * @property float|null $stageLocation
 * @property mixed $points
 * @property mixed $centers
 * @property string|null $observations
 * @property int|null $oldId
 * @property-read Area $area
 * @property-read Collection|Door[] $doors
 * @property-read int|null $doorsCount
 * @property-read Collection|Seat[] $seats
 * @property-read int|null $seatsCount
 * @method static SavitarBuilder|Sector newModelQuery()
 * @method static SavitarBuilder|Sector newQuery()
 * @method static SavitarBuilder|Sector query()
 * @method static Builder|Sector whereAreaId($value)
 * @method static Builder|Sector whereCenters($value)
 * @method static Builder|Sector whereColumns($value)
 * @method static Builder|Sector whereCreatedAt($value)
 * @method static Builder|Sector whereCreatedBy($value)
 * @method static Builder|Sector whereDeletedAt($value)
 * @method static Builder|Sector whereDeletedBy($value)
 * @method static Builder|Sector whereDisabledAccess($value)
 * @method static Builder|Sector whereOrder($value)
 * @method static Builder|Sector whereId($value)
 * @method static Builder|Sector whereIsNumbered($value)
 * @method static Builder|Sector whereName($value)
 * @method static Builder|Sector whereObservations($value)
 * @method static Builder|Sector whereOldId($value)
 * @method static Builder|Sector wherePoints($value)
 * @method static Builder|Sector whereReducedVisibility($value)
 * @method static Builder|Sector whereRows($value)
 * @method static Builder|Sector whereStageLocation($value)
 * @method static Builder|Sector whereTicketName($value)
 * @method static Builder|Sector whereTotalSeats($value)
 * @method static Builder|Sector whereUpdatedAt($value)
 * @method static Builder|Sector whereUpdatedBy($value)
 * @method static Builder|Sector whereWebName($value)
 * @mixin Eloquent
 */
class Sector extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Sector.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'Sector.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'Sector.ticketName',
                'default' => true,
            ],
            'order' => [
                'name' => 'Orden',
                'type' => 'int',
                'sql' => 'Sector.order',
                'default' => true,
            ],
            'totalSeats' => [
                'name' => 'Aforo',
                'type' => 'number',
                'sql' => 'Sector.totalSeats',
                'default' => true,
            ],
            'rows' => [
                'name' => 'Filas',
                'type' => 'number',
                'sql' => 'Sector.rows',
                'default' => false,
            ],
            'columns' => [
                'name' => 'Columnas',
                'type' => 'number',
                'sql' => 'Sector.columns',
                'default' => false,
            ],
            'isNumbered' => [
                'name' => 'Sector numerado',
                'type' => 'boolean',
                'sql' => 'Sector.isNumbered',
            ],
            'disabledAccess' => [
                'name' => 'Acceso para minusválidos',
                'type' => 'boolean',
                'sql' => 'Sector.disabledAccess',
            ],
            'reducedVisibility' => [
                'name' => 'Visibilidad reducida',
                'type' => 'boolean',
                'sql' => 'Sector.reducedVisibility',
            ],
            'stageLocation' => [
                'name' => 'Orientación',
                'type' => 'number',
                'sql' => 'Sector.stageLocation',
            ],
            // stageLocation ? it's an angle, not needed in datagrids
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Sector.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Sector.createdAt',
                'default' => true,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Sector.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Sector.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Sector.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'area' => [
                'name' => 'Área',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Area::class,
            ],
            /*
            'doors' => [
                'name' => 'Puertas',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Door::class,
            ],
            */
            /*
            'seats' => [
                'name' => 'Butacas',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Seat::class,
                'relatedModelControllerClass' => SeatController::class,
            ],
            */
        ],
    ];

    /**
     * A sector belongs to an area.
     *
     * @return BelongsTo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * @return BelongsToMany
     */
    public function doors(): BelongsToMany
    {
        return $this->belongsToMany(Door::class)
            ->using(DoorSector::class)
            ->withTimestamps()
            ->orderBy('order');
    }

    /**
     * A sector has many seats.
     *
     * @return HasMany
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
