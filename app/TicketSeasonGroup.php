<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\TicketSeasonGroup
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $name
 * @property string|null $observations
 * @property int|null $oldId
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clientsCount
 * @property-read Collection|TicketSeason[] $ticketSeasons
 * @property-read int|null $ticketSeasonsCount
 * @method static SavitarBuilder|TicketSeasonGroup newModelQuery()
 * @method static SavitarBuilder|TicketSeasonGroup newQuery()
 * @method static SavitarBuilder|TicketSeasonGroup query()
 * @method static Builder|TicketSeasonGroup whereCreatedAt($value)
 * @method static Builder|TicketSeasonGroup whereCreatedBy($value)
 * @method static Builder|TicketSeasonGroup whereDeletedAt($value)
 * @method static Builder|TicketSeasonGroup whereDeletedBy($value)
 * @method static Builder|TicketSeasonGroup whereId($value)
 * @method static Builder|TicketSeasonGroup whereName($value)
 * @method static Builder|TicketSeasonGroup whereObservations($value)
 * @method static Builder|TicketSeasonGroup whereUpdatedAt($value)
 * @method static Builder|TicketSeasonGroup whereUpdatedBy($value)
 * @method static Builder|TicketSeasonGroup whereOldId($value)
 * @mixin Eloquent
 */
class TicketSeasonGroup extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'TicketSeasonGroup.name',
                'default' => true,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'TicketSeasonGroup.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'TicketSeasonGroup.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'TicketSeasonGroup.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'TicketSeasonGroup.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'TicketSeasonGroup.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'clients' => [
                'name' => 'Clientes',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Client::class,
            ],
        ],
    ];

    /**
     * @return HasMany
     */
    public function ticketSeasons(): HasMany
    {
        return $this->hasMany(TicketSeason::class);
    }

    /**
     * @return BelongsToMany
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class)->using(ClientTicketSeasonGroup::class)->withTimestamps();
    }
}
