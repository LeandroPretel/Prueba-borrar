<?php

namespace App;

use Eloquent;
use Exception;
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
 * App\ShowTemplate
 *
 * @property string $id
 * @property string $slug
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string|null $description
 * @property string|null $classification
 * @property int|null $duration
 * @property int|null $break
 * @property string|null $additionalInfo
 * @property string|null $videoId
 * @property string|null $showClassificationId
 * @property string|null $observations
 * @property string|null $password
 * @property bool hasPassword
 * @property int|null $oldId
 * @property-read Collection|Artist[] $artists
 * @property-read int|null $artistsCount
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read mixed|string $mainImageUrl
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @property-read Collection|ShowCategory[] $showCategories
 * @property-read int|null $showCategoriesCount
 * @property-read User|null $updatedByUser
 * @property-read ShowClassification|null $showClassification
 * @method static Builder|ShowTemplate whereShowClassificationId($value)
 * @method static SavitarBuilder|ShowTemplate newModelQuery()
 * @method static SavitarBuilder|ShowTemplate newQuery()
 * @method static SavitarBuilder|ShowTemplate query()
 * @method static Builder|ShowTemplate whereAccountId($value)
 * @method static Builder|ShowTemplate whereSlug($value)
 * @method static Builder|ShowTemplate whereAdditionalInfo($value)
 * @method static Builder|ShowTemplate whereBreak($value)
 * @method static Builder|ShowTemplate whereClassification($value)
 * @method static Builder|ShowTemplate whereCreatedAt($value)
 * @method static Builder|ShowTemplate whereCreatedBy($value)
 * @method static Builder|ShowTemplate whereDeletedAt($value)
 * @method static Builder|ShowTemplate whereDeletedBy($value)
 * @method static Builder|ShowTemplate whereDescription($value)
 * @method static Builder|ShowTemplate whereDuration($value)
 * @method static Builder|ShowTemplate whereId($value)
 * @method static Builder|ShowTemplate whereName($value)
 * @method static Builder|ShowTemplate whereVideoId($value)
 * @method static Builder|ShowTemplate whereUpdatedAt($value)
 * @method static Builder|ShowTemplate whereUpdatedBy($value)
 * @method static Builder|ShowTemplate whereTicketName($value)
 * @method static Builder|ShowTemplate whereWebName($value)
 * @method static Builder|ShowTemplate whereObservations($value)
 * @method static Builder|ShowTemplate whereHasPassword($value)
 * @method static Builder|ShowTemplate wherePassword($value)
 * @method static Builder|ShowTemplate whereOldId($value)
 * @mixin Eloquent
 * @property bool $hasPassword
 */
class ShowTemplate extends SavitarModel
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
                'sql' => 'ShowTemplate.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'ShowTemplate.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'ShowTemplate.ticketName',
                'default' => true,
            ],
            'description' => [
                'name' => 'Descripción',
                'type' => 'html',
                'sql' => 'ShowTemplate.description',
            ],
            'showClassificationName' => [
                'name' => 'Clasificación',
                'type' => 'string',
                'sql' => 'ShowClassification.name',
                'foreignKey' => 'ShowTemplate.showClassificationId',
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'duration' => [
                'name' => 'Duración (minutos)',
                'type' => 'int',
                'sql' => 'ShowTemplate.duration',
                'default' => true,
            ],
            'break' => [
                'name' => 'Descanso (minutos)',
                'type' => 'int',
                'sql' => 'ShowTemplate.break',
                'default' => true,
            ],
            'additionalInfo' => [
                'name' => 'Información adicional',
                'type' => 'string',
                'sql' => 'ShowTemplate.additionalInfo',
            ],
            'videoId' => [
                'name' => 'Video',
                'type' => 'string',
                'sql' => 'ShowTemplate.videoId',
            ],
            'hasPassword' => [
                'name' => 'Protegido con contraseña',
                'type' => 'boolean',
                'sql' => 'ShowTemplate.hasPassword',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'ShowTemplate.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'ShowTemplate.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'ShowTemplate.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'ShowTemplate.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'ShowTemplate.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'artists' => [
                'name' => 'Artistas',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Artist::class,
            ],
            'showCategories' => [
                'name' => 'Categorías',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => ShowCategory::class,
            ],
            'sessions' => [
                'name' => 'Sesiones',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Session::class,
            ],
            'files' => [
                'name' => 'Archivos',
                'type' => 'relation',
                'relationType' => 'morphMany',
                'relatedModelClass' => SavitarFile::class,
            ],
        ],
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @var array
     */
    protected $appends = ['mainImageUrl'];

    protected $filesInputKeys = ['files', 'mainImage'];

    /**
     * @return mixed|string
     * @throws Exception
     */
    public function getMainImageUrlAttribute()
    {
        $photo = $this->files()->where('category', 'mainImage')->first();
        return $photo ? $photo->url : 'assets/event-placeholder.svg';
    }

    /**
     * A ShowTemplate belongs to a classification
     *
     * @return BelongsTo
     */
    public function showClassification(): BelongsTo
    {
        return $this->belongsTo(ShowClassification::class);
    }

    /**
     * A ShowTemplate has many sessions
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * @return BelongsToMany
     */
    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class)->using(ArtistShowTemplate::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function showCategories(): BelongsToMany
    {
        return $this->belongsToMany(ShowCategory::class)->using(ShowCategoryShowTemplate::class)->withTimestamps();
    }
}
