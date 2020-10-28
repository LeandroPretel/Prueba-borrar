<?php

namespace App;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Auth\SavitarZone;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Enterprise
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string|null $countryId
 * @property string $provinceId
 * @property string $name
 * @property string $socialReason
 * @property string $nif
 * @property string $city
 * @property string $address
 * @property string $zipCode
 * @property string|null $phone
 * @property string|null $email
 * @property bool $chargeToAccount
 * @property string|null $contactName
 * @property string|null $contactNif
 * @property string|null $contactEmail
 * @property string|null $chargeIban
 * @property string|null $paymentIban
 * @property string|null $observations
 * @property bool $requireMinCommission
 * @property float|null $minCommission
 * @property int|null $oldId
 * @property string|null $anfixCustomerId
 * @property int|null $anfixCustomerCompanyAccountingAccountNumber
 * @property string|null $anfixSupplierId
 * @property int|null $anfixSupplierCompanyAccountingAccountNumber
 * @property int|null $anfixCompanyAccountingAccountNumber
 * @property-read SavitarZone|null $country
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read mixed|string $mainImageUrl
 * @property-read Collection|PointOfSale[] $pointsOfSale
 * @property-read int|null $pointsOfSaleCount
 * @property-read SavitarZone $province
 * @property-read Collection|Account[] $accounts
 * @property-read int|null $accountsCount
 * @property-read Collection|Partner[] $partners
 * @property-read int|null $partnersCount
 * @method static SavitarBuilder|Enterprise newModelQuery()
 * @method static SavitarBuilder|Enterprise newQuery()
 * @method static SavitarBuilder|Enterprise query()
 * @method static Builder|Enterprise whereAddress($value)
 * @method static Builder|Enterprise whereChargeToAccount($value)
 * @method static Builder|Enterprise whereCity($value)
 * @method static Builder|Enterprise whereCountryId($value)
 * @method static Builder|Enterprise whereCreatedAt($value)
 * @method static Builder|Enterprise whereCreatedBy($value)
 * @method static Builder|Enterprise whereDeletedAt($value)
 * @method static Builder|Enterprise whereDeletedBy($value)
 * @method static Builder|Enterprise whereEmail($value)
 * @method static Builder|Enterprise whereChargeIban($value)
 * @method static Builder|Enterprise wherePaymentIban($value)
 * @method static Builder|Enterprise whereId($value)
 * @method static Builder|Enterprise whereName($value)
 * @method static Builder|Enterprise whereNif($value)
 * @method static Builder|Enterprise whereObservations($value)
 * @method static Builder|Enterprise wherePhone($value)
 * @method static Builder|Enterprise whereProvinceId($value)
 * @method static Builder|Enterprise whereUpdatedAt($value)
 * @method static Builder|Enterprise whereUpdatedBy($value)
 * @method static Builder|Enterprise whereZipCode($value)
 * @method static Builder|Enterprise whereContactName($value)
 * @method static Builder|Enterprise whereContactNif($value)
 * @method static Builder|Enterprise whereContactEmail($value)
 * @method static Builder|Enterprise whereSocialReason($value)
 * @method static Builder|Enterprise whereMinCommission($value)
 * @method static Builder|Enterprise whereOldId($value)
 * @method static Builder|Enterprise whereRequireMinCommission($value)
 * @method static Builder|Enterprise whereAnfixCompanyAccountingAccountNumber($value)
 * @method static Builder|Enterprise whereAnfixCustomerCompanyAccountingAccountNumber($value)
 * @method static Builder|Enterprise whereAnfixCustomerId($value)
 * @method static Builder|Enterprise whereAnfixSupplierCompanyAccountingAccountNumber($value)
 * @method static Builder|Enterprise whereAnfixSupplierId($value)
 * @mixin Eloquent
 */
class Enterprise extends SavitarModel
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
                'sql' => 'Enterprise.name',
                'default' => true,
            ],
            'socialReason' => [
                'name' => 'Razón social',
                'type' => 'string',
                'sql' => 'Enterprise.socialReason',
                'default' => true,
            ],
            'nif' => [
                'name' => 'CIF/NIF',
                'type' => 'string',
                'sql' => 'Enterprise.nif',
                'default' => true,
            ],
            'provinceName' => [
                'name' => 'Provincia',
                'type' => 'string',
                'sql' => 'Zone.name',
                'foreignKey' => 'Enterprise.provinceId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'city' => [
                'name' => 'Localidad',
                'type' => 'string',
                'sql' => 'Enterprise.city',
            ],
            'address' => [
                'name' => 'Dirección',
                'type' => 'string',
                'sql' => 'Enterprise.address',
            ],
            'zipCode' => [
                'name' => 'Código Postal',
                'type' => 'string',
                'sql' => 'Enterprise.zipCode',
            ],
            'phone' => [
                'name' => 'Teléfono',
                'type' => 'string',
                'sql' => 'Enterprise.phone',
            ],
            'email' => [
                'name' => 'Email',
                'type' => 'string',
                'sql' => 'Enterprise.email',
            ],
            'chargeIban' => [
                'name' => 'IBAN Cobros',
                'type' => 'string',
                'sql' => 'Enterprise.chargeIban',
            ],
            'paymentIban' => [
                'name' => 'IBAN Pagos',
                'type' => 'string',
                'sql' => 'Enterprise.paymentIban',
            ],
            'chargeToAccount' => [
                'name' => 'Cargo en cuenta',
                'type' => 'boolean',
                'sql' => 'Enterprise.chargeToAccount',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'requireMinCommission' => [
                'name' => 'Requiere comisión mínima',
                'type' => 'boolean',
                'sql' => 'Enterprise.requireMinCommission',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'minCommission' => [
                'name' => 'Comisión mínima',
                'type' => 'number',
                'sql' => 'Enterprise.minCommission',
            ],
            'contactName' => [
                'name' => 'Nombre de contacto',
                'type' => 'string',
                'sql' => 'Enterprise.contactName',
            ],
            'contactNif' => [
                'name' => 'NIF de contacto',
                'type' => 'string',
                'sql' => 'Enterprise.contactNif',
            ],
            'contactEmail' => [
                'name' => 'Email de contacto',
                'type' => 'string',
                'sql' => 'Enterprise.contactEmail',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Enterprise.observations',
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
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(SavitarZone::class, 'countryId');
    }

    /**
     * @return BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(SavitarZone::class, 'provinceId');
    }

    /**
     * Get all of the points of sale that are assigned this enterprise.
     */
    public function pointsOfSale()
    {
        return $this->morphedByMany(PointOfSale::class, 'enterprisable', 'Enterprisable');
    }

    /**
     * Get all of the accounts that are assigned this enterprise.
     */
    public function accounts()
    {
        return $this->morphedByMany(Account::class, 'enterprisable', 'Enterprisable');
    }

    /**
     * Get all of the partners that are assigned this enterprise.
     */
    public function partners()
    {
        return $this->morphedByMany(Partner::class, 'enterprisable', 'Enterprisable');
    }
}
