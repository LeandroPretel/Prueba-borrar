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
 * App\SessionSector
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $sessionId
 * @property string $sessionAreaId
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
 * @property-read SessionArea $sessionArea
 * @property-read Collection|SessionDoor[] $sessionDoors
 * @property-read int|null $sessionDoorsCount
 * @property-read Collection|SessionSeat[] $sessionSeats
 * @property-read int|null $sessionSeatsCount
 * @property-read Session $session
 * @method static SavitarBuilder|SessionSector newModelQuery()
 * @method static SavitarBuilder|SessionSector newQuery()
 * @method static SavitarBuilder|SessionSector query()
 * @method static Builder|SessionSector whereCenters($value)
 * @method static Builder|SessionSector whereColumns($value)
 * @method static Builder|SessionSector whereCreatedAt($value)
 * @method static Builder|SessionSector whereCreatedBy($value)
 * @method static Builder|SessionSector whereDeletedAt($value)
 * @method static Builder|SessionSector whereDeletedBy($value)
 * @method static Builder|SessionSector whereDisabledAccess($value)
 * @method static Builder|SessionSector whereId($value)
 * @method static Builder|SessionSector whereIsNumbered($value)
 * @method static Builder|SessionSector whereName($value)
 * @method static Builder|SessionSector whereObservations($value)
 * @method static Builder|SessionSector whereOldId($value)
 * @method static Builder|SessionSector wherePoints($value)
 * @method static Builder|SessionSector whereReducedVisibility($value)
 * @method static Builder|SessionSector whereRows($value)
 * @method static Builder|SessionSector whereSessionAreaId($value)
 * @method static Builder|SessionSector whereSessionId($value)
 * @method static Builder|SessionSector whereStageLocation($value)
 * @method static Builder|SessionSector whereTicketName($value)
 * @method static Builder|SessionSector whereTotalSeats($value)
 * @method static Builder|SessionSector whereUpdatedAt($value)
 * @method static Builder|SessionSector whereUpdatedBy($value)
 * @method static Builder|SessionSector whereWebName($value)
 * @method static Builder|SessionSector whereOrder($value)
 * @mixin Eloquent
 */
class SessionSector extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'SessionSector.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'SessionSector.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'SessionSector.ticketName',
                'default' => true,
            ],
            'order' => [
                'name' => 'Orden',
                'type' => 'int',
                'sql' => 'SessionSector.order',
                'default' => true,
            ],
            'totalSeats' => [
                'name' => 'Aforo',
                'type' => 'number',
                'sql' => 'SessionSector.totalSeats',
                'default' => true,
            ],
            'rows' => [
                'name' => 'Filas',
                'type' => 'number',
                'sql' => 'SessionSector.rows',
                'default' => false,
            ],
            'columns' => [
                'name' => 'Columnas',
                'type' => 'number',
                'sql' => 'SessionSector.columns',
                'default' => false,
            ],
            'isNumbered' => [
                'name' => 'Sector numerado',
                'type' => 'boolean',
                'sql' => 'SessionSector.isNumbered',
            ],
            'disabledAccess' => [
                'name' => 'Acceso para minusválidos',
                'type' => 'boolean',
                'sql' => 'SessionSector.disabledAccess',
            ],
            'reducedVisibility' => [
                'name' => 'Visibilidad reducida',
                'type' => 'boolean',
                'sql' => 'SessionSector.reducedVisibility',
            ],
            'stageLocation' => [
                'name' => 'Orientación',
                'type' => 'number',
                'sql' => 'SessionSector.stageLocation',
            ],
            // stageLocation ? it's an angle, not needed in datagrids
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'SessionSector.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'SessionSector.createdAt',
                'default' => true,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'SessionSector.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'SessionSector.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'SessionSector.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'session' => [
                'name' => 'Sesión',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Session::class,
            ],
            'sessionArea' => [
                'name' => 'Área',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SessionArea::class,
            ],

        ],
    ];

    /**
     * A sessionSector belongs to a session
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * A sessionSector belongs to a sessionArea
     *
     * @return BelongsTo
     */
    public function sessionArea(): BelongsTo
    {
        return $this->belongsTo(SessionArea::class);
    }

    /**
     * @return BelongsToMany
     */
    public function sessionDoors(): BelongsToMany
    {
        return $this->belongsToMany(SessionDoor::class)
            ->using(SessionDoorSessionSector::class)
            ->withTimestamps()
            ->orderBy('order');
    }

    /**
     * A sessionSector has many sessionArea
     *
     * @return HasMany
     */
    public function sessionSeats(): HasMany
    {
        return $this->hasMany(SessionSeat::class);
    }
}
