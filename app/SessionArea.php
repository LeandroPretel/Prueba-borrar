<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\SessionArea
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $sessionId
 * @property string $spaceId
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string $color
 * @property int $totalSeats
 * @property string|null $observations
 * @property int|null $oldId
 * @property int $order
 * @property-read Collection|Fare[] $fares
 * @property-read int|null $faresCount
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read Session $session
 * @property-read Collection|SessionSector[] $sessionSectors
 * @property-read int|null $sessionSectorsCount
 * @property-read Space $space
 * @method static SavitarBuilder|SessionArea newModelQuery()
 * @method static SavitarBuilder|SessionArea newQuery()
 * @method static SavitarBuilder|SessionArea query()
 * @method static Builder|SessionArea whereColor($value)
 * @method static Builder|SessionArea whereCreatedAt($value)
 * @method static Builder|SessionArea whereCreatedBy($value)
 * @method static Builder|SessionArea whereDeletedAt($value)
 * @method static Builder|SessionArea whereDeletedBy($value)
 * @method static Builder|SessionArea whereId($value)
 * @method static Builder|SessionArea whereName($value)
 * @method static Builder|SessionArea whereObservations($value)
 * @method static Builder|SessionArea whereOldId($value)
 * @method static Builder|SessionArea whereSessionId($value)
 * @method static Builder|SessionArea whereSpaceId($value)
 * @method static Builder|SessionArea whereTicketName($value)
 * @method static Builder|SessionArea whereTotalSeats($value)
 * @method static Builder|SessionArea whereUpdatedAt($value)
 * @method static Builder|SessionArea whereUpdatedBy($value)
 * @method static Builder|SessionArea whereWebName($value)
 * @method static Builder|SessionArea whereOrder($value)
 * @mixin Eloquent
 */
class SessionArea extends SavitarModel
{
    use HasFiles;

    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'SessionArea.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'SessionArea.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'SessionArea.ticketName',
                'default' => true,
            ],
            'order' => [
                'name' => 'Orden',
                'type' => 'int',
                'sql' => 'SessionArea.order',
                'default' => true,
            ],
            'totalSeats' => [
                'name' => 'Aforo',
                'type' => 'number',
                'sql' => 'SessionArea.totalSeats',
                'default' => true,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'SessionArea.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'SessionArea.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'SessionArea.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'SessionArea.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'SessionArea.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'session' => [
                'name' => 'Sesión',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Session::class,
            ],
            'space' => [
                'name' => 'Espacio',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Space::class,
            ],
            'sessionSectors' => [
                'name' => 'Sectores',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => SessionSector::class,
                // 'relatedModelControllerClass' => SectorController::class,
            ],
            'color' => [
                'name' => 'Color',
                'type' => 'string',
                'sql' => 'SessionArea.color',
            ],
        ],
    ];

    /**
     * A sessionArea belongs to a session
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * An area belongs to a space
     *
     * @return BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * An area has many sectors
     *
     * @return HasMany
     */
    public function sessionSectors(): HasMany
    {
        return $this->hasMany(SessionSector::class)
            ->orderBy('order')
            ->orderBy('createdAt', 'asc');
    }

    /**
     * An area belongsToMany fares
     *
     * @return BelongsToMany
     */
    public function fares(): BelongsToMany
    {
        return $this->belongsToMany(Fare::class, 'SessionAreaFare')->using(SessionAreaFare::class)
            ->withPivot([
                'id',
                'isActive',
                'earlyPrice',
                'earlyDistributionPrice',
                'earlyTotalPrice',
                'ticketOfficePrice',
                'ticketOfficeDistributionPrice',
                'ticketOfficeTotalPrice',
                'createdAt',
                'updatedAt',
            ]);
    }
}
