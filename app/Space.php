<?php

namespace App;

use App\Http\Controllers\AreaController;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Space
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
 * @property string $denomination
 * @property string|null $description
 * @property string|null $observations
 * @property int|null $maximumCapacity
 * @property int|null $oldId
 * @property-read Collection|Area[] $areas
 * @property-read int|null $areasCount
 * @property-read Collection|Area[] $sessionAreas
 * @property-read int|null $sessionAreasCount
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read mixed|string $mainImageUrl
 * @property-read Place $place
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|Space newModelQuery()
 * @method static SavitarBuilder|Space newQuery()
 * @method static SavitarBuilder|Space query()
 * @method static Builder|Space whereCreatedAt($value)
 * @method static Builder|Space whereCreatedBy($value)
 * @method static Builder|Space whereDeletedAt($value)
 * @method static Builder|Space whereDeletedBy($value)
 * @method static Builder|Space whereDenomination($value)
 * @method static Builder|Space whereDescription($value)
 * @method static Builder|Space whereId($value)
 * @method static Builder|Space whereMaximumCapacity($value)
 * @method static Builder|Space whereName($value)
 * @method static Builder|Space wherePlaceId($value)
 * @method static Builder|Space whereUpdatedAt($value)
 * @method static Builder|Space whereUpdatedBy($value)
 * @method static Builder|Space whereTicketName($value)
 * @method static Builder|Space whereWebName($value)
 * @method static Builder|Space whereObservations($value)
 * @method static Builder|Space whereOldId($value)
 * @mixin Eloquent
 */
class Space extends SavitarModel
{
    use HasFiles;

    protected $modelDefinition = [
        'visibleAttributes' => [
            'mainImageUrl' => [
                'name' => 'Imagen',
                'type' => 'icon',
                'sql' => 'mainImageUrl',
                'notSortable' => true,
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Space.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'Space.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'Space.ticketName',
                'default' => true,
            ],
            'denomination' => [
                'name' => 'Denominación escenario',
                'type' => 'string',
                'sql' => 'Space.denomination',
            ],
            'maximumCapacity' => [
                'name' => 'Aforo máximo',
                'type' => 'int',
                'sql' => 'Space.maximumCapacity',
                'default' => true,
            ],
            'areasCount' => [
                'name' => 'Áreas',
                'type' => 'count',
                'countRelation' => 'areas',
                'sql' => 'areasCount',
                'default' => true,
            ],
            'description' => [
                'name' => 'Descripción',
                'type' => 'string',
                'sql' => 'Space.description',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Space.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Space.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Space.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Space.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Space.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'place' => [
                'name' => 'Recinto',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Place::class,
            ],
            'areas' => [
                'name' => 'Áreas',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Area::class,
                'relatedModelControllerClass' => AreaController::class,
            ],
            'sessionAreas' => [
                'name' => 'Áreas (Eventos)',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => SessionArea::class,
            ],
            'files' => [
                'name' => 'Archivos',
                'type' => 'relation',
                'relationType' => 'morphMany',
                'relatedModelClass' => SavitarFile::class,
            ],
        ],
    ];

    protected $appends = ['mainImageUrl'];

    protected $filesInputKeys = ['files', 'mainImage'];

    /**
     * @return mixed|string
     * @throws Exception
     */
    public function getMainImageUrlAttribute()
    {
        $photo = $this->files()->where('category', 'mainImage')->first();
        return $photo ? $photo->url : 'assets/aforo.svg';
    }

    /**
     * A space belongs to a place
     *
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * A space has many areas
     *
     * @return HasMany
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Area::class)
            ->orderBy('order')
            ->orderBy('name');
    }

    /**
     * A space has many sessionAreas
     *
     * @return HasMany
     */
    public function sessionAreas(): HasMany
    {
        return $this->hasMany(SessionArea::class)
            ->orderBy('order')
            ->orderBy('name');
    }

    /**
     * A space has many sessions
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }
}
