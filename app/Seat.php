<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBaseModel;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\Traits\UsesUUID;

/**
 * App\Seat
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string $sectorId
 * @property int|null $row
 * @property int|null $column
 * @property string|null $rowName
 * @property string|null $number
 * @property bool $isForDisabled
 * @property bool $reducedVisibility
 * @property string $status
 * @property string|null $lockReason
 * @property string|null $observations
 * @property-read Collection|Door[] $doors
 * @property-read int|null $doorsCount
 * @property-read Sector $sector
 * @method static SavitarBuilder|Seat newModelQuery()
 * @method static SavitarBuilder|Seat newQuery()
 * @method static SavitarBuilder|Seat query()
 * @method static Builder|Seat whereColumn($value)
 * @method static Builder|Seat whereCreatedAt($value)
 * @method static Builder|Seat whereId($value)
 * @method static Builder|Seat whereIsForDisabled($value)
 * @method static Builder|Seat whereNumber($value)
 * @method static Builder|Seat whereLockReason($value)
 * @method static Builder|Seat whereObservations($value)
 * @method static Builder|Seat whereReducedVisibility($value)
 * @method static Builder|Seat whereRow($value)
 * @method static Builder|Seat whereRowName($value)
 * @method static Builder|Seat whereSectorId($value)
 * @method static Builder|Seat whereStatus($value)
 * @method static Builder|Seat whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Seat extends SavitarBaseModel
{
    use UsesUUID;

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
                'sql' => 'Seat.isForDisabled',
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
                'sql' => 'Seat.status',
                'possibleValues' => ['enabled', 'disabled', 'deleted', 'locked', 'reserved'],
                'configuration' => [
                    'enabled' => ['html' => 'Habilitada', 'translation' => 'Habilitada'],
                    'disabled' => ['html' => 'Deshabilitada', 'translation' => 'Deshabilitada'],
                    'deleted' => ['html' => 'Eliminada', 'translation' => 'Eliminada'],
                    'locked' => ['html' => 'Bloqueada', 'translation' => 'Bloqueada'],
                    'reserved' => ['html' => 'Reservada', 'translation' => 'Reservada'],
                ],
            ],
            'lockReason' => [
                'name' => 'Motivo bloqueo',
                'type' => 'string',
                'sql' => 'Seat.lockReason',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Seat.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Seat.createdAt',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Seat.updatedAt',
            ],
        ],
        'shadowAttributes' => [
            'id' => [
                'name' => 'UUID',
                'validation' => 'uuid|required',
                'type' => 'string',
                'update' => false,
                'save' => false,
            ],
            'row' => [
                'name' => 'Fila',
                'type' => 'int',
                'sql' => 'Seat.row',
            ],
            'column' => [
                'name' => 'Columna',
                'type' => 'int',
                'sql' => 'Seat.column',
            ],
            'sector' => [
                'name' => 'Sector',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Sector::class,
            ],
        ],
    ];

    public $controlVariables = false;

    /**
     * A seat belongs to a sector.
     *
     * @return BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    /**
     * @return BelongsToMany
     */
    public function doors(): BelongsToMany
    {
        return $this->belongsToMany(Door::class)->using(DoorSeat::class)->withTimestamps();
    }
}
