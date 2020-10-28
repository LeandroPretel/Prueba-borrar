<?php

namespace App;

use App\Http\Controllers\DoorController;
use App\Http\Controllers\SpaceController;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Auth\SavitarZone;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Place
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string|null $countryId
 * @property string|null $provinceId
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string|null $bannerText
 * @property string|null $bannerTextColor
 * @property bool $bannerTextIsVisible
 * @property string|null $city
 * @property string|null $address
 * @property string|null $zipCode
 * @property string|null $description
 * @property string|null $mapLink
 * @property string|null $observations
 * @property bool $hasAccessControl
 * @property int|null $oldId
 * @property-read SavitarZone|null $country
 * @property-read Collection|Door[] $doors
 * @property-read int|null $doorsCount
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read mixed|string $mainImageUrl
 * @property-read Collection|PointOfSale[] $pointsOfSale
 * @property-read int|null $pointsOfSaleCount
 * @property-read SavitarZone|null $province
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @property-read Collection|Space[] $spaces
 * @property-read int|null $spacesCount
 * @method static SavitarBuilder|Place newModelQuery()
 * @method static SavitarBuilder|Place newQuery()
 * @method static SavitarBuilder|Place query()
 * @method static Builder|Place whereAddress($value)
 * @method static Builder|Place whereBannerText($value)
 * @method static Builder|Place whereBannerTextColor($value)
 * @method static Builder|Place whereBannerTextIsVisible($value)
 * @method static Builder|Place whereCity($value)
 * @method static Builder|Place whereCountryId($value)
 * @method static Builder|Place whereCreatedAt($value)
 * @method static Builder|Place whereCreatedBy($value)
 * @method static Builder|Place whereDeletedAt($value)
 * @method static Builder|Place whereDeletedBy($value)
 * @method static Builder|Place whereDescription($value)
 * @method static Builder|Place whereHasAccessControl($value)
 * @method static Builder|Place whereId($value)
 * @method static Builder|Place whereName($value)
 * @method static Builder|Place whereProvinceId($value)
 * @method static Builder|Place whereTicketName($value)
 * @method static Builder|Place whereUpdatedAt($value)
 * @method static Builder|Place whereUpdatedBy($value)
 * @method static Builder|Place whereWebName($value)
 * @method static Builder|Place whereZipCode($value)
 * @method static Builder|Place whereMapLink($value)
 * @method static Builder|Place whereObservations($value)
 * @method static Builder|Place whereOldId($value)
 * @mixin Eloquent
 */
class Place extends SavitarModel
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
                'sql' => 'Place.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'Place.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'Place.ticketName',
                'default' => true,
            ],
            'provinceName' => [
                'name' => 'Provincia',
                'type' => 'string',
                'sql' => 'Zone.name',
                'foreignKey' => 'Place.provinceId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'bannerText' => [
                'name' => 'Texto banner',
                'type' => 'string',
                'sql' => 'Place.bannerText',
            ],
            'bannerTextColor' => [
                'name' => 'Color Texto banner',
                'type' => 'string',
                'sql' => 'Place.bannerTextColor',
            ],
            'bannerTextIsVisible' => [
                'name' => 'Texto banner visible',
                'type' => 'boolean',
                'sql' => 'Place.bannerTextIsVisible',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'city' => [
                'name' => 'Localidad',
                'type' => 'string',
                'sql' => 'Place.city',
                'default' => true,
            ],
            'address' => [
                'name' => 'Dirección',
                'type' => 'string',
                'sql' => 'Place.address',
            ],
            'zipCode' => [
                'name' => 'Código Postal',
                'type' => 'string',
                'sql' => 'Place.zipCode',
            ],
            'description' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Place.description',
            ],
            'mapLink' => [
                'name' => 'Mapa',
                'type' => 'string',
                'sql' => 'Place.mapLink',
            ],
            'hasAccessControl' => [
                'name' => 'Control de accesos',
                'type' => 'boolean',
                'sql' => 'Place.hasAccessControl',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
                'default' => true,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Place.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Place.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Place.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Place.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Place.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'country' => [
                'name' => 'País',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SavitarZone::class,
            ],
            'province' => [
                'name' => 'Provincia',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SavitarZone::class,
            ],
            'doors' => [
                'name' => 'Puertas',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Door::class,
                'relatedModelControllerClass' => DoorController::class,
            ],
            'spaces' => [
                'name' => 'Aforos',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Space::class,
                'relatedModelControllerClass' => SpaceController::class,
            ],
            'files' => [
                'name' => 'Archivos',
                'type' => 'relation',
                'relationType' => 'morphMany',
                'relatedModelClass' => SavitarFile::class,
            ],
            'pointsOfSale' => [
                'name' => 'Taquillas',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => PointOfSale::class,
            ],
        ],
    ];

    protected $appends = ['mainImageUrl'];

    protected $filesInputKeys = ['files', 'mainImage'];

    /**
     * The main image of the place. (1200x300)
     *
     * @return mixed|string
     * @throws Exception
     */
    public function getMainImageUrlAttribute()
    {
        $photo = $this->files()->where('category', 'mainImage')->first();
        return $photo ? $photo->url : 'assets/place-placeholder.svg';
    }

    /**
     * A place has many doors.
     *
     * @return HasMany
     */
    public function doors(): HasMany
    {
        return $this->hasMany(Door::class);
    }

    /**
     * A place has many spaces.
     *
     * @return HasMany
     */
    public function spaces(): HasMany
    {
        return $this->hasMany(Space::class);
    }

    /**
     * A place belongs to a province.
     *
     * @return BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(SavitarZone::class, 'provinceId');
    }

    /**
     * A place belongs to a country.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(SavitarZone::class, 'countryId');
    }

    /**
     * A place has many sessions.
     *
     * @return HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * A place belongs to many points of sale.
     *
     * @return BelongsToMany
     */
    public function pointsOfSale(): BelongsToMany
    {
        return $this->belongsToMany(PointOfSale::class)->using(PlacePointOfSale::class)->withTimestamps();
    }
}
