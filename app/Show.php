<?php

namespace App;

use App\Http\Controllers\SessionController;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;
use Savitar\Models\Traits\AccountRelatable;

/**
 * App\Show
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $accountId
 * @property mixed|string $name
 * @property string|null $webName
 * @property string|null $ticketName
 * @property string|null $slug
 * @property mixed|string $description
 * @property bool $isFeatured
 * @property string|null $featuredText
 * @property bool $associatedToTuPalacio
 * @property string|null $videoId
 * @property string|null $observations
 * @property bool $appearsOnRedentradas
 * @property int|null $oldId
 * @property-read Account $account
 * @property-read Collection|Brand[] $brands
 * @property-read int|null $brandsCount
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read mixed|string $featuredImageUrl
 * @property-read mixed|string $mainImageUrl
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @method static SavitarBuilder|Show newModelQuery()
 * @method static SavitarBuilder|Show newQuery()
 * @method static SavitarBuilder|Show query()
 * @method static Builder|Show whereAccountId($value)
 * @method static Builder|Show whereAssociatedToTuPalacio($value)
 * @method static Builder|Show whereCreatedAt($value)
 * @method static Builder|Show whereCreatedBy($value)
 * @method static Builder|Show whereDeletedAt($value)
 * @method static Builder|Show whereDeletedBy($value)
 * @method static Builder|Show whereDescription($value)
 * @method static Builder|Show whereFeaturedText($value)
 * @method static Builder|Show whereId($value)
 * @method static Builder|Show whereIsFeatured($value)
 * @method static Builder|Show whereName($value)
 * @method static Builder|Show whereObservations($value)
 * @method static Builder|Show whereSlug($value)
 * @method static Builder|Show whereTicketName($value)
 * @method static Builder|Show whereUpdatedAt($value)
 * @method static Builder|Show whereUpdatedBy($value)
 * @method static Builder|Show whereVideoId($value)
 * @method static Builder|Show whereWebName($value)
 * @method static Builder|Show whereOldId($value)
 * @method static Builder|Show whereAppearsOnRedentradas($value)
 * @mixin Eloquent
 */
class Show extends SavitarModel
{
    use AccountRelatable;
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
                'sql' => 'Show.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'Show.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'Show.ticketName',
                'default' => true,
            ],
            'description' => [
                'name' => 'Descripción',
                'type' => 'html',
                'sql' => 'Show.description',
            ],
            'metaDescription' => [
                'name' => 'Descripción meta tag',
                'type' => 'html',
                'sql' => 'Show.metaDescription',
            ],
            'accountName' => [
                'name' => 'Promotor',
                'type' => 'string',
                'sql' => 'Account.name',
                'foreignKey' => 'Show.accountId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            /*
            'contract' => [
                'type' => 'string',
                'sql' => 'Show.contract',
                'default' => true,
            ],
            */
            'sessionsCount' => [
                'name' => 'Sesiones',
                'type' => 'count',
                'countRelation' => 'sessions',
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'isFeatured' => [
                'name' => 'Evento destacado',
                'type' => 'boolean',
                'sql' => 'Show.isFeatured',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'featuredText' => [
                'name' => 'Texto destacado',
                'type' => 'string',
                'sql' => 'Show.featuredText',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Show.observations',
            ],
            'associatedToTuPalacio' => [
                'name' => 'Evento asociado a Tu Palacio',
                'type' => 'boolean',
                'sql' => 'Show.associatedToTuPalacio',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'videoId' => [
                'name' => 'Video',
                'type' => 'string',
                'sql' => 'Show.videoId',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Show.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Show.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Show.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Show.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'account' => [
                'name' => 'Promotor',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Account::class,
            ],
            'sessions' => [
                'name' => 'Sesiones',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Session::class,
                'relatedModelControllerClass' => SessionController::class,
            ],
        ],
    ];

    /**
     * @var array
     */
    protected $appends = ['mainImageUrl', 'featuredImageUrl'];

    protected $filesInputKeys = ['files', 'mainImage', 'featuredImage'];

    /**
     * @return mixed|string
     * @throws Exception
     */
    public function getMainImageUrlAttribute()
    {
        $photo = $this->files()->where('category', 'mainImage')->first();
        if (!$photo && $this->sessions()->count() > 0) {
            $photo = SavitarFile::whereFileableId($this->sessions()->first()->showTemplateId)->where('category', 'mainImage')->first();
        }
        return $photo ? $photo->url : 'assets/event-placeholder.svg';
    }

    /**
     * Returns the featured image or mainImage if doesnt exist. (1200x300)
     *
     * @return mixed|string
     * @throws Exception
     */
    public function getFeaturedImageUrlAttribute()
    {
        $photo = $this->files()->where('category', 'featuredImage')->first();
        return $photo ? $photo->url : null;
    }

    /**
     * @param string|null $name
     * @return mixed|string
     */
    public function getNameAttribute(?string $name)
    {
        if ($name) {
            return $name;
        }
        $firstSession = $this->sessions()->with('showTemplate')->first();
        return $firstSession ? $firstSession->showTemplate->name : null;
    }

    /**
     * @param string|null $webName
     * @return string|null
     */
    public function getWebNameAttribute(?string $webName): ?string
    {
        if ($webName) {
            return $webName;
        }
        $firstSession = $this->sessions()->with('showTemplate')->first();
        return $firstSession ? $firstSession->showTemplate->webName : null;
    }

    /**
     * @param string|null $ticketName
     * @return string|null
     */
    public function getTicketNameAttribute(?string $ticketName): ?string
    {
        if ($ticketName) {
            return $ticketName;
        }
        $firstSession = $this->sessions()->with('showTemplate')->first();
        return $firstSession ? $firstSession->showTemplate->ticketName : null;
    }

    /**
     * @param string|null $description
     * @return mixed|string
     */
    public function getDescriptionAttribute(?string $description)
    {
        if ($description) {
            return $description;
        }
        // If the show has sessions with diferent showTemplates it's calle a "cicle" (ciclo), if the show is a cicle,
        // don't return the name of the first session but if it isn't, all the sessions has the same showTemplate
        // and return it's description
        $isACicle = false;
        $showTemplateId = null;
        $index = 0;
        /** @var Session $session */
        foreach ($this->sessions()->with('showTemplate')->get() as $session) {
            if($index === 0) {
                $showTemplateId = $session->showTemplateId;
            } else {
                if ($session->showTemplateId !== $showTemplateId) {
                    $showTemplateId2 = $session->showTemplate->id;
                    $isACicle = true;
                    break;
                }
            }
            $index++;
        }
        if (!$isACicle) {
            $firstSession = $this->sessions()->with('showTemplate')->first();
            return $firstSession ? $firstSession->showTemplate->description : null;
        }
    }

    /**
     * A show has many sessions
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class)
            ->select([
                'id', 'webName', 'ticketName', 'showId', 'showTemplateId', 'placeId', 'spaceId', 'defaultFareId',
                'date', 'status', 'displayAsSoon',
            ])
            ->orderBy('date', 'asc');
    }

    /**
     * A Show belongs to many Brands (the Brands in which the Show will be displayed at the client page of the Brand).
     *
     * @return BelongsToMany
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class)->using(BrandShow::class)->withTimestamps();
    }
}
