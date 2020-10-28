<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Savitar\Auth\Controllers\BillingDataController;
use Savitar\Auth\SavitarAccount;
use Savitar\Auth\SavitarBillingData;
use Savitar\Auth\SavitarUser;
use Savitar\Auth\SavitarZone;
use Savitar\Files\SavitarFile;
use Savitar\Models\SavitarBuilder;

/**
 * App\Account
 *
 * @property string $id
 * @property string|null $countryId
 * @property string|null $provinceId
 * @property string $name
 * @property string $slug
 * @property string|null $nif
 * @property bool $isActive
 * @property string|null $city
 * @property string|null $address
 * @property string|null $zipCode
 * @property string|null $phone
 * @property string|null $fax
 * @property string|null $website
 * @property string|null $email
 * @property string|null $facebookUrl
 * @property string|null $twitterUrl
 * @property string|null $youtubeUrl
 * @property string|null $instagramUrl
 * @property string $selectedTheme
 * @property bool $initialConfiguration
 * @property bool $isSuperAdminAccount
 * @property string|null $observations
 * @property string|null $contactName
 * @property string|null $contactPhone
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $anfixCustomerId
 * @property string|null $anfixCompanyAccountingAccountNumber
 * @property float|null $maximumAdvance
 * @property bool $canCreateInvitations
 * @property bool $unlimitedInvitations
 * @property int|null $oldId
 * @property-read Collection|SavitarBillingData[] $billingData
 * @property-read int|null $billingDataCount
 * @property-read SavitarZone|null $country
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read string|null $lastLicenseStatus
 * @property-read mixed|string $logoUrl
 * @property-read string|null $planName
 * @property-read Collection|PointOfSale[] $pointsOfSale
 * @property-read int|null $pointsOfSaleCount
 * @property-read SavitarZone|null $province
 * @property-read Collection|ShowTemplate[] $showTemplates
 * @property-read int|null $showTemplatesCount
 * @property-read Collection|Show[] $shows
 * @property-read int|null $showsCount
 * @property-read User|null $updatedByUser
 * @property-read Collection|SavitarUser[] $users
 * @property-read int|null $usersCount
 * @property-read Collection|Enterprise[] $enterprises
 * @property-read int|null $enterprisesCount
 * @method static SavitarBuilder|Account newModelQuery()
 * @method static SavitarBuilder|Account newQuery()
 * @method static SavitarBuilder|Account query()
 * @method static Builder|Account whereAddress($value)
 * @method static Builder|Account whereCity($value)
 * @method static Builder|Account whereContactName($value)
 * @method static Builder|Account whereContactPhone($value)
 * @method static Builder|Account whereCountryId($value)
 * @method static Builder|Account whereCreatedAt($value)
 * @method static Builder|Account whereCreatedBy($value)
 * @method static Builder|Account whereDeletedAt($value)
 * @method static Builder|Account whereDeletedBy($value)
 * @method static Builder|Account whereEmail($value)
 * @method static Builder|Account whereFacebookUrl($value)
 * @method static Builder|Account whereFax($value)
 * @method static Builder|Account whereId($value)
 * @method static Builder|Account whereInitialConfiguration($value)
 * @method static Builder|Account whereInstagramUrl($value)
 * @method static Builder|Account whereIsActive($value)
 * @method static Builder|Account whereIsSuperAdminAccount($value)
 * @method static Builder|Account whereName($value)
 * @method static Builder|Account whereNif($value)
 * @method static Builder|Account whereObservations($value)
 * @method static Builder|Account wherePhone($value)
 * @method static Builder|Account whereProvinceId($value)
 * @method static Builder|Account whereSelectedTheme($value)
 * @method static Builder|Account whereSlug($value)
 * @method static Builder|Account whereTwitterUrl($value)
 * @method static Builder|Account whereUpdatedAt($value)
 * @method static Builder|Account whereUpdatedBy($value)
 * @method static Builder|Account whereWebsite($value)
 * @method static Builder|Account whereYoutubeUrl($value)
 * @method static Builder|Account whereZipCode($value)
 * @method static Builder|Account whereCanCreateInvitations($value)
 * @method static Builder|Account whereMaximumAdvance($value)
 * @method static Builder|Account whereUnlimitedInvitations($value)
 * @method static Builder|Account whereAnfixCompanyAccountingAccountNumber($value)
 * @method static Builder|Account whereAnfixCustomerId($value)
 * @method static Builder|Account whereOldId($value)
 * @mixin Eloquent
 */
class Account extends SavitarAccount
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Account.name',
                'default' => true,
            ],
            'isActive' => [
                'name' => 'Estado',
                'type' => 'boolean',
                'sql' => 'Account.isActive',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'Bloqueado', 'translation' => 'Bloqueado', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Activo', 'translation' => 'Activo', 'statusColor' => 'green-status'],
                    // 'true' => ['html' => 'Activo', 'translation' => 'Activo', 'customColor' => '#359964'],
                ],
                'default' => true,
            ],
            'nif' => [
                'name' => 'CIF/NIF',
                'type' => 'string',
                'sql' => 'Account.nif',
            ],
            'initialConfiguration' => [
                'name' => 'Config. inicial realizada',
                'type' => 'boolean',
                'sql' => 'Account.initialConfiguration',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'provinceName' => [
                'name' => 'Provincia',
                'type' => 'string',
                'sql' => 'Zone.name',
                'foreignKey' => 'Account.provinceId',
                'update' => false,
                'save' => false,
            ],
            'city' => [
                'name' => 'Localidad',
                'type' => 'string',
                'sql' => 'Account.city',
            ],
            'address' => [
                'name' => 'Dirección',
                'type' => 'string',
                'sql' => 'Account.address',
            ],
            'zipCode' => [
                'name' => 'Código Postal',
                'type' => 'string',
                'sql' => 'Account.zipCode',
            ],
            'phone' => [
                'name' => 'Teléfono',
                'type' => 'string',
                'sql' => 'Account.phone',
                'default' => true,
            ],
            'email' => [
                'name' => 'Email',
                'type' => 'string',
                'sql' => 'Account.email',
            ],
            'website' => [
                'name' => 'Web',
                'type' => 'string',
                'sql' => 'Account.website',
            ],
            'maximumAdvance' => [
                'name' => 'Máximo anticipado',
                'type' => 'money',
                'sql' => 'Account.maximumAdvance',
            ],
            'canCreateInvitations' => [
                'name' => 'Puede crear invitaciones',
                'type' => 'boolean',
                'sql' => 'Account.canCreateInvitations',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'unlimitedInvitations' => [
                'name' => 'Invitaciones ilimitadas',
                'type' => 'boolean',
                'sql' => 'Account.unlimitedInvitations',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Account.observations',
            ],
            'contactName' => [
                'name' => 'Nombre de la persona de contacto',
                'type' => 'string',
                'sql' => 'Account.contactName',
            ],
            'contactPhone' => [
                'name' => 'Teléfono de la persona de contacto',
                'type' => 'string',
                'sql' => 'Account.contactPhone',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Account.createdAt',
                'default' => true,
                'accountConfiguration' => false,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Account.createdBy',
                'accountConfiguration' => false,
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Account.updatedAt',
                'accountConfiguration' => false,
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Account.updatedBy',
                'accountConfiguration' => false,
            ],
        ],
        'shadowAttributes' => [
            'id' => [
                'name' => 'UUID',
                'validation' => 'uuid|required',
                'type' => 'string',
                'update' => false,
                'save' => false,
                'accountConfiguration' => false,
            ],
            'deletedAt' => [
                'name' => 'Fecha de eliminación',
                'validation' => 'date|required',
                'type' => 'date',
                'update' => false,
                'save' => false,
                'accountConfiguration' => false,
            ],
            'deletedBy' => [
                'name' => 'Eliminado por',
                'type' => 'string',
                'sql' => 'Account.deletedBy',
                'accountConfiguration' => false,
            ],
            'fax' => [
                'name' => 'Fax',
                'type' => 'string',
                'sql' => 'Account.fax',
            ],
            'users' => [
                'name' => 'Usuario',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => SavitarUser::class,
            ],
            'billingData' => [
                'name' => 'Datos de facturación',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => SavitarBillingData::class,
                'relatedModelControllerClass' => BillingDataController::class,
            ],
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
            'files' => [
                'name' => 'Archivos',
                'type' => 'relation',
                'relationType' => 'morphMany',
                'relatedModelClass' => SavitarFile::class,
            ],
            'pointsOfSale' => [
                'name' => 'Puntos de venta',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => PointOfSale::class,
            ],
        ],
    ];

    /**
     * A sponsor has many shows
     *
     * @return HasMany
     */
    public function shows(): HasMany
    {
        return $this->hasMany(Show::class);
    }

    /**
     * A sponsor has many showTemplates
     *
     * @return HasMany
     */
    public function showTemplates(): HasMany
    {
        return $this->hasMany(ShowTemplate::class);
    }

    /**
     * An account belongs to many points of sale
     *
     * @return BelongsToMany
     */
    public function pointsOfSale(): BelongsToMany
    {
        return $this->belongsToMany(PointOfSale::class)->using(AccountPointOfSale::class)->withTimestamps();
    }

    /**
     * An account has many files
     *
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(SavitarFile::class, 'fileableId');
    }

    /**
     * Get all of the enterprises for the account.
     */
    public function enterprises(): MorphToMany
    {
        return $this->morphToMany(Enterprise::class, 'enterprisable', 'Enterprisable');
    }
}
