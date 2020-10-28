<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Savitar\Auth\SavitarZone;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarModel;

/**
 * App\Brand
 *
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $name
 * @property string|null $domain
 * @property string $selectedTheme
 * @property string|null $observations
 * @property string|null $email
 * @property string|null $facebookUrl
 * @property string|null $twitterUrl
 * @property string|null $phone
 * @property string|null $city
 * @property string|null $address
 * @property string|null $zipCode
 * @property string|null $provinceId
 * @property bool $associatedToRedentradasShows
 * @property-read \Illuminate\Database\Eloquent\Collection|\Savitar\Files\SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read mixed|string $brandLogoUrl
 * @property-read \Savitar\Auth\SavitarZone|null $province
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Show[] $shows
 * @property-read int|null $showsCount
 * @method static \Savitar\Models\SavitarBuilder|\App\Brand newModelQuery()
 * @method static \Savitar\Models\SavitarBuilder|\App\Brand newQuery()
 * @method static \Savitar\Models\SavitarBuilder|\App\Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereAssociatedToRedentradasShows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereFacebookUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereObservations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereSelectedTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereTwitterUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Brand whereZipCode($value)
 * @mixin \Eloquent
 */
class Brand extends SavitarModel
{
    use HasFiles;

    static $availableThemes = [
        "bee-red-theme" => "#E53935",
        "bee-pink-theme" => "#D81B60",
        "bee-purple-theme" => "#8E24AA",
        "bee-deep-purple-theme" => "#5E35B1",
        "bee-deep-green-theme" => "#0A6C2D",
        "bee-green-theme" => "#43A047",
        "bee-turquoise-theme" => "#cb2b99",
        "bee-redentradas-theme" => "#cb2b99",
        "bee-cyan-theme" => "#01ACC1",
        "bee-blue-theme" => "#1E88E5",
        "bee-deep-blue-theme" => "#3948AB",
        "bee-yellow-theme" => "#FDD835",
        "bee-amber-theme" => "#CF9307",
        "bee-orange-theme" => "#D17603",
        "bee-deep-orange-theme" => "#BC3308",
        "bee-black-theme" => "#1E1E1E",
        "bee-deep-grey-theme" => "#4E4E4E",
        "bee-blue-grey-theme" => "#546E7A",
        "bee-grey-theme" => "#757575",
        "bee-brown-theme" => "#6D4C41",
    ];

    use HasFiles;

    protected $modelDefinition = [
        'visibleAttributes' => [
            'brandLogoUrl' => [
                'name' => 'Logo',
                'type' => 'icon',
                'sql' => 'brandLogoUrl',
                'notSortable' => true,
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Brand.name',
                'default' => true,
            ],
            'domain' => [
                'name' => 'Dominio',
                'type' => 'string',
                'sql' => 'Brand.domain',
                'default' => true,
            ],
            'selectedTheme' => [
                'name' => 'Tema',
                'type' => 'array',
                'sql' => 'Brand.selectedTheme',
                'possibleValues' => [],
                'configuration' => [],
            ],
            'associatedToRedentradasShows' => [
                'name' => 'Asociado a los eventos de redentradas.com',
                'type' => 'boolean',
                'sql' => 'Brand.associatedToRedentradasShows',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí', 'statusColor' => 'green-status'],
                ],
                'default' => true,
            ],
            'facebookUrl' => [
                'name' => 'Facebook',
                'type' => 'string',
                'sql' => 'Brand.facebookUrl',
                'default' => true,
            ],
            'twitterUrl' => [
                'name' => 'Twitter',
                'type' => 'string',
                'sql' => 'Brand.twitterUrl',
                'default' => true,
            ],
            'provinceName' => [
                'name' => 'Provincia',
                'type' => 'string',
                'sql' => 'Zone.name',
                'foreignKey' => 'Brand.provinceId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'city' => [
                'name' => 'Localidad',
                'type' => 'string',
                'sql' => 'Brand.city',
            ],
            'address' => [
                'name' => 'Dirección',
                'type' => 'string',
                'sql' => 'Brand.address',
            ],
            'zipCode' => [
                'name' => 'Código Postal',
                'type' => 'string',
                'sql' => 'Brand.zipCode',
            ],
            'phone' => [
                'name' => 'Teléfono',
                'type' => 'string',
                'sql' => 'Brand.phone',
            ],
            'email' => [
                'name' => 'Email',
                'type' => 'string',
                'sql' => 'Brand.email',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Brand.observations',
                'default' => true,
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Brand.createdAt',
                'default' => true,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Brand.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Brand.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Brand.updatedBy',
            ],

        ],
        'shadowAttributes' => [
            'files' => [
                'name' => 'Archivos',
                'type' => 'relation',
                'relationType' => 'morphMany',
                'relatedModelClass' => SavitarFile::class,
            ],
            'province' => [
                'name' => 'Provincia',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SavitarZone::class,
            ],
        ]
    ];

    protected $appends = ['brandLogoUrl'];

    protected $filesInputKeys = ['files', 'brandLogo'];

    /**
     * The logo image of the Brand. (1200x300)
     *
     * @return mixed|string
     * @throws Exception
     */
    public function getBrandLogoUrlAttribute()
    {
        $photo = $this->files()->where('category', 'brandLogo')->first();
        return $photo ? $photo->url : 'assets/place-placeholder.svg';
    }

    /**
     * A brand belongs to a province.
     *
     * @return BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(SavitarZone::class, 'provinceId');
    }


    /**
     * A Brand belongs to many Shows (the ones which will be displayed at the client page of the Brand).
     *
     * @return BelongsToMany
     */
    public function shows(): BelongsToMany
    {
        return $this->belongsToMany(Show::class)->using(BrandShow::class)->withPivot('associatedToRedentradas')->withTimestamps();
    }
}
