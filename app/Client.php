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
use Savitar\Auth\SavitarZone;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Client
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $userId
 * @property string $name
 * @property string $surname
 * @property string $nif
 * @property string $phone
 * @property string|null $birthDate
 * @property bool $associatedToTuPalacio
 * @property bool $preferHomeDelivery
 * @property string|null $countryId
 * @property string|null $provinceId
 * @property string|null $fax
 * @property string|null $city
 * @property string|null $address
 * @property string|null $zipCode
 * @property string|null $observations
 * @property int|null $oldId
 * @property bool $isSeasonClient
 * @property-read Collection|Order[] $accesses
 * @property-read int|null $accessesCount
 * @property-read mixed|string $profileImageUrl
 * @property-read Collection|Order[] $orders
 * @property-read int|null $ordersCount
 * @property-read Collection|Ticket[] $tickets
 * @property-read int|null $ticketsCount
 * @property-read User $user
 * @property-read SavitarZone|null $country
 * @property-read SavitarZone|null $province
 * @property-read Collection|TicketSeasonGroup[] $ticketSeasonGroups
 * @property-read int|null $ticketSeasonGroupsCount
 * @method static SavitarBuilder|Client newModelQuery()
 * @method static SavitarBuilder|Client newQuery()
 * @method static SavitarBuilder|Client query()
 * @method static Builder|Client whereAssociatedToTuPalacio($value)
 * @method static Builder|Client whereBirthDate($value)
 * @method static Builder|Client whereCreatedAt($value)
 * @method static Builder|Client whereCreatedBy($value)
 * @method static Builder|Client whereDeletedAt($value)
 * @method static Builder|Client whereDeletedBy($value)
 * @method static Builder|Client whereId($value)
 * @method static Builder|Client whereName($value)
 * @method static Builder|Client whereNif($value)
 * @method static Builder|Client wherePhone($value)
 * @method static Builder|Client whereSurname($value)
 * @method static Builder|Client whereUpdatedAt($value)
 * @method static Builder|Client whereUpdatedBy($value)
 * @method static Builder|Client whereUserId($value)
 * @method static Builder|Client whereAddress($value)
 * @method static Builder|Client whereCity($value)
 * @method static Builder|Client whereCountryId($value)
 * @method static Builder|Client whereFax($value)
 * @method static Builder|Client whereObservations($value)
 * @method static Builder|Client whereProvinceId($value)
 * @method static Builder|Client whereZipCode($value)
 * @method static Builder|Client wherePreferHomeDelivery($value)
 * @method static Builder|Client whereIsSeasonClient($value)
 * @method static Builder|Client whereOldId($value)
 * @mixin Eloquent
 */
class Client extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'profileImageUrl' => [
                'name' => 'Imagen',
                'type' => 'icon',
                'sql' => 'profileImageUrl',
                'notSortable' => true,
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Client.name',
                'default' => true,
            ],
            'surname' => [
                'name' => 'Apellidos',
                'type' => 'string',
                'sql' => 'Client.surname',
                'default' => true,
            ],
            'countryName' => [
                'name' => 'País',
                'type' => 'string',
                'sql' => 'Zone.name',
                'foreignKey' => 'Client.countryId',
                'update' => false,
                'save' => false,
            ],
            'provinceName' => [
                'name' => 'Provincia',
                'type' => 'string',
                'sql' => 'Zone.name',
                'foreignKey' => 'Client.provinceId',
                'update' => false,
                'save' => false,
            ],
            'city' => [
                'name' => 'Localidad',
                'type' => 'string',
                'sql' => 'Client.city',
            ],
            'address' => [
                'name' => 'Dirección',
                'type' => 'string',
                'sql' => 'Client.address',
            ],
            'zipCode' => [
                'name' => 'Código Postal',
                'type' => 'string',
                'sql' => 'Client.zipCode',
            ],
            'phone' => [
                'name' => 'Teléfono',
                'type' => 'string',
                'sql' => 'Client.phone',
            ],
            'fax' => [
                'name' => 'Fax',
                'type' => 'string',
                'sql' => 'Client.fax',
            ],
            'email' => [
                'name' => 'Email',
                'type' => 'string',
                'sql' => 'User.email',
                'foreignKey' => 'Client.userId',
                'default' => true,
                'update' => false,
                'save' => false,
                'profile' => false,
            ],
            /*
            'roleName' => [
                'name' => 'Tipo',
                'type' => 'string',
                'sql' => 'Role.name',
                'default' => true,
                'foreignKey' => 'User.roleId',
                'update' => false,
                'save' => false,
            ],
            */
            'nif' => [
                'name' => 'NIF/NIE',
                'type' => 'string',
                'sql' => 'Client.nif',
                'default' => true,
            ],
            'isActive' => [
                'name' => 'Estado',
                'type' => 'boolean',
                'sql' => 'User.isActive',
                'foreignKey' => 'Client.userId',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'Bloqueado', 'translation' => 'Bloqueado', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Activo', 'translation' => 'Activo', 'statusColor' => 'green-status'],
                ],
                'default' => true,
                'update' => false,
                'save' => false,
            ],
            'emailConfirmed' => [
                'name' => 'Estado email',
                'type' => 'boolean',
                'sql' => 'User.emailConfirmed',
                'foreignKey' => 'Client.userId',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No verificado', 'translation' => 'No verificado', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Verificado', 'translation' => 'Verificado', 'statusColor' => 'green-status'],
                ],
                'update' => false,
                'save' => false,
            ],
            'preferHomeDelivery' => [
                'name' => 'Prefiere envío a domicilio',
                'type' => 'boolean',
                'sql' => 'Client.preferHomeDelivery',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'associatedToTuPalacio' => [
                'name' => 'Asociado a Tu Palacio',
                'type' => 'boolean',
                'sql' => 'Client.associatedToTuPalacio',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Client.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Client.createdAt',
                'default' => true,
                'profile' => false,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Client.createdBy',
                'profile' => false,
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Client.updatedAt',
                'profile' => false,
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Client.updatedBy',
                'profile' => false,
            ],
        ],
        'shadowAttributes' => [
            'id' => [
                'name' => 'UUID',
                'validation' => 'uuid|required',
                'type' => 'string',
                'update' => false,
                'save' => false,
                'profile' => false,
            ],
            'deletedAt' => [
                'name' => 'Fecha de eliminación',
                'validation' => 'date|required',
                'type' => 'date',
                'update' => false,
                'save' => false,
                'profile' => false,
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
            'ticketSeasonGroups' => [
                'name' => 'Grupos de abonado',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => TicketSeasonGroup::class,
            ],
        ],
    ];

    protected $appends = ['profileImageUrl'];

    /**
     * @return mixed|string
     * @throws Exception
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->userId) {
            $user = User::where('id', $this->userId)->withTrashed()->first();
            $photo = $user->files()->where('category', 'profileImage')->first();
            return $photo ? $photo->url : 'app/shared/assets/images/avatar-placeholder.png';
        }
        return null;
    }

    /**
     * A client belongs to an user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * A client belongs to a province.
     *
     * @return BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(SavitarZone::class, 'provinceId');
    }

    /**
     * A client belongs to a country.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(SavitarZone::class, 'countryId');
    }

    /**
     * A client has many orders.
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * A client has many tickets.
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * A client has many accesses.
     *
     * @return HasMany
     */
    public function accesses(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return BelongsToMany
     */
    public function ticketSeasonGroups(): BelongsToMany
    {
        return $this->belongsToMany(TicketSeasonGroup::class)->using(ClientTicketSeasonGroup::class)->withTimestamps();
    }
}
