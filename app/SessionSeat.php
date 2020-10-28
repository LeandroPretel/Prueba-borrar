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
 * App\SessionSeat
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $sessionId
 * @property string $sessionSectorId
 * @property string|null $fareId
 * @property int|null $row
 * @property int|null $column
 * @property string|null $rowName
 * @property string|null $number
 * @property bool $isForDisabled
 * @property bool $reducedVisibility
 * @property string $status
 * @property string|null $lockReason
 * @property string|null $observations
 * @property int|null $oldId
 * @property-read Collection|Access[] $accesses
 * @property-read int|null $accessesCount
 * @property-read Session $session
 * @property-read Collection|SessionDoor[] $sessionDoors
 * @property-read int|null $sessionDoorsCount
 * @property-read SessionSector $sessionSector
 * @property-read Collection|Ticket[] $tickets
 * @property-read int|null $ticketsCount
 * @method static SavitarBuilder|SessionSeat newModelQuery()
 * @method static SavitarBuilder|SessionSeat newQuery()
 * @method static SavitarBuilder|SessionSeat query()
 * @method static Builder|SessionSeat whereColumn($value)
 * @method static Builder|SessionSeat whereCreatedAt($value)
 * @method static Builder|SessionSeat whereCreatedBy($value)
 * @method static Builder|SessionSeat whereDeletedAt($value)
 * @method static Builder|SessionSeat whereDeletedBy($value)
 * @method static Builder|SessionSeat whereFareId($value)
 * @method static Builder|SessionSeat whereId($value)
 * @method static Builder|SessionSeat whereIsForDisabled($value)
 * @method static Builder|SessionSeat whereLockReason($value)
 * @method static Builder|SessionSeat whereNumber($value)
 * @method static Builder|SessionSeat whereObservations($value)
 * @method static Builder|SessionSeat whereOldId($value)
 * @method static Builder|SessionSeat whereReducedVisibility($value)
 * @method static Builder|SessionSeat whereRow($value)
 * @method static Builder|SessionSeat whereRowName($value)
 * @method static Builder|SessionSeat whereSessionId($value)
 * @method static Builder|SessionSeat whereSessionSectorId($value)
 * @method static Builder|SessionSeat whereStatus($value)
 * @method static Builder|SessionSeat whereUpdatedAt($value)
 * @method static Builder|SessionSeat whereUpdatedBy($value)
 * @mixin Eloquent
 */
class SessionSeat extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'rowName' => [
                'name' => 'Fila',
                'type' => 'string',
                'sql' => 'Seat.rowName',
            ],
            'number' => [
                'name' => 'Número',
                'type' => 'string',
                'sql' => 'Seat.number',
            ],
            'isForDisabled' => [
                'name' => 'Acceso a minusválidos',
                'type' => 'boolean',
                'sql' => 'SessionSeat.isForDisabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'reducedVisibility' => [
                'name' => 'Visibilidad reducida',
                'type' => 'boolean',
                'sql' => 'Seat.reducedVisibility',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'status' => [
                'name' => 'Estado',
                'type' => 'array',
                'sql' => 'SessionSeat.status',
                'possibleValues' => ['enabled', 'deleted', 'locked', 'reserved', 'sold', 'hard-ticket', 'invitation'],
                'configuration' => [
                    'enabled' => ['html' => 'Habilitada', 'translation' => 'Habilitada'],
                    'deleted' => ['html' => 'Eliminada', 'translation' => 'Eliminada'],
                    'locked' => ['html' => 'Bloqueada', 'translation' => 'Bloqueada'],
                    'reserved' => ['html' => 'Reservada', 'translation' => 'Reservada'],
                    'sold' => ['html' => 'Reservada', 'translation' => 'Vendida'],
                    'hard-ticket' => ['html' => 'Hard Ticket', 'translation' => 'Hard ticket'],
                    'invitation' => ['html' => 'Invitación', 'translation' => 'Invitación'],
                ],
            ],
            'lockReason' => [
                'name' => 'Motivo bloqueo',
                'type' => 'string',
                'sql' => 'SessionSeat.lockReason',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'SessionSeat.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'SessionSeat.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'SessionSeat.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'SessionSeat.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'SessionSeat.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'row' => [
                'name' => 'Fila',
                'type' => 'int',
                'sql' => 'SessionSeat.row',
            ],
            'column' => [
                'name' => 'Columna',
                'type' => 'int',
                'sql' => 'SessionSeat.column',
            ],
            'session' => [
                'name' => 'Sesión',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Session::class,
            ],
            'sessionSector' => [
                'name' => 'Sector',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SessionSector::class,
            ],
            'tickets' => [
                'name' => 'Entradas',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Ticket::class,
            ],
        ],
    ];

    /**
     * A sessionSeat belongsTo a session.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * A sessionSeat belongsTo a sessionSector.
     *
     * @return BelongsTo
     */
    public function sessionSector(): BelongsTo
    {
        return $this->belongsTo(SessionSector::class);
    }

    /**
     * A sessionSeat has many tickets. (or may not have it)
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * A sessionSeat has many accesses. (or may not have it)
     *
     * @return HasMany
     */
    public function accesses(): HasMany
    {
        return $this->hasMany(Access::class);
    }

    /**
     * @return BelongsToMany
     */
    public function sessionDoors(): BelongsToMany
    {
        return $this->belongsToMany(SessionDoor::class)->using(SessionDoorSessionSeat::class)
            ->withTimestamps();
    }
}
