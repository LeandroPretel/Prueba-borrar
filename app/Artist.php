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
 * App\Artist
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string|null $observations
 * @property int|null $oldId
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read Collection|ShowTemplate[] $showTemplates
 * @property-read int|null $showTemplatesCount
 * @property-read Collection|Show[] $shows
 * @property-read int|null $showsCount
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|Artist newModelQuery()
 * @method static SavitarBuilder|Artist newQuery()
 * @method static SavitarBuilder|Artist query()
 * @method static Builder|Artist whereCreatedAt($value)
 * @method static Builder|Artist whereCreatedBy($value)
 * @method static Builder|Artist whereDeletedAt($value)
 * @method static Builder|Artist whereDeletedBy($value)
 * @method static Builder|Artist whereId($value)
 * @method static Builder|Artist whereName($value)
 * @method static Builder|Artist whereUpdatedAt($value)
 * @method static Builder|Artist whereUpdatedBy($value)
 * @method static Builder|Artist whereObservations($value)
 * @method static Builder|Artist whereTicketName($value)
 * @method static Builder|Artist whereWebName($value)
 * @method static Builder|Artist whereOldId($value)
 * @mixin Eloquent
 */
class Artist extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Artist.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'Artist.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'Artist.ticketName',
                'default' => true,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Artist.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Artist.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Artist.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Artist.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Artist.updatedBy',
            ],
        ],
    ];

    /**
     * An artist has many shows
     *
     * @return HasMany
     */
    public function shows(): HasMany
    {
        return $this->hasMany(Show::class);
    }

    /**
     * An artist has many showsTemplates
     *
     * @return BelongsToMany
     */
    public function showTemplates(): BelongsToMany
    {
        return $this->belongsToMany(ShowTemplate::class)->using(ArtistShowTemplate::class)->withTimestamps();
    }
}
