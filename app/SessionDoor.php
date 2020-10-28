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
 * App\SessionDoor
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $sessionId
 * @property string $placeId
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string|null $observations
 * @property int|null $oldId
 * @property int $order
 * @property-read Place $place
 * @property-read Session $session
 * @property-read Collection|SessionSeat[] $sessionSeats
 * @property-read int|null $sessionSeatsCount
 * @property-read Collection|SessionSector[] $sessionSectors
 * @property-read int|null $sessionSectorsCount
 * @method static SavitarBuilder|SessionDoor newModelQuery()
 * @method static SavitarBuilder|SessionDoor newQuery()
 * @method static SavitarBuilder|SessionDoor query()
 * @method static Builder|SessionDoor whereCreatedAt($value)
 * @method static Builder|SessionDoor whereCreatedBy($value)
 * @method static Builder|SessionDoor whereDeletedAt($value)
 * @method static Builder|SessionDoor whereDeletedBy($value)
 * @method static Builder|SessionDoor whereId($value)
 * @method static Builder|SessionDoor whereName($value)
 * @method static Builder|SessionDoor whereObservations($value)
 * @method static Builder|SessionDoor whereOldId($value)
 * @method static Builder|SessionDoor wherePlaceId($value)
 * @method static Builder|SessionDoor whereSessionId($value)
 * @method static Builder|SessionDoor whereTicketName($value)
 * @method static Builder|SessionDoor whereUpdatedAt($value)
 * @method static Builder|SessionDoor whereUpdatedBy($value)
 * @method static Builder|SessionDoor whereWebName($value)
 * @method static Builder|SessionDoor whereOrder($value)
 * @mixin Eloquent
 */
class SessionDoor extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'SessionDoor.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'SessionDoor.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'SessionDoor.ticketName',
                'default' => true,
            ],
            'order' => [
                'name' => 'Orden',
                'type' => 'int',
                'sql' => 'SessionDoor.order',
                'default' => true,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'SessionDoor.observations',
                'default' => true,
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'SessionDoor.createdAt',
                'default' => true,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'SessionDoor.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'SessionDoor.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'SessionDoor.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'session' => [
                'name' => 'Sesión',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Session::class,
            ],
            'place' => [
                'name' => 'Recinto',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Place::class,
            ],
            'sessionSectors' => [
                'name' => 'Sectores',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => SessionSector::class,
            ],
        ],
    ];

    /**
     * A sessionDoor belongs to a session
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * A sessionDoor belongs to a place
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
    public function sessionSectors(): BelongsToMany
    {
        return $this->belongsToMany(SessionSector::class)
            ->using(SessionDoorSessionSector::class)
            ->withTimestamps()
            ->orderBy('order');
    }

    /**
     * @return BelongsToMany
     */
    public function sessionSeats(): BelongsToMany
    {
        return $this->belongsToMany(SessionSeat::class)
            ->using(SessionDoorSessionSeat::class)
            ->withTimestamps();
    }
}
