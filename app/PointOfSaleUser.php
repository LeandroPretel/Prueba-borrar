<?php

namespace App;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\PointOfSaleUser
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $pointOfSaleId
 * @property string $userId
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read mixed|string $profileImageUrl
 * @property-read Collection|Order[] $orders
 * @property-read int|null $ordersCount
 * @property-read PointOfSale $pointOfSale
 * @property-read Collection|Ticket[] $tickets
 * @property-read int|null $ticketsCount
 * @property-read User|null $updatedByUser
 * @property-read User $user
 * @method static SavitarBuilder|PointOfSaleUser newModelQuery()
 * @method static SavitarBuilder|PointOfSaleUser newQuery()
 * @method static SavitarBuilder|PointOfSaleUser query()
 * @method static Builder|PointOfSaleUser whereCreatedAt($value)
 * @method static Builder|PointOfSaleUser whereCreatedBy($value)
 * @method static Builder|PointOfSaleUser whereDeletedAt($value)
 * @method static Builder|PointOfSaleUser whereDeletedBy($value)
 * @method static Builder|PointOfSaleUser whereId($value)
 * @method static Builder|PointOfSaleUser wherePointOfSaleId($value)
 * @method static Builder|PointOfSaleUser whereUpdatedAt($value)
 * @method static Builder|PointOfSaleUser whereUpdatedBy($value)
 * @method static Builder|PointOfSaleUser whereUserId($value)
 * @mixin Eloquent
 */
class PointOfSaleUser extends SavitarModel
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
            'pointOfSaleName' => [
                'name' => 'Punto de venta',
                'type' => 'string',
                'sql' => 'PointOfSale.name',
                'foreignKey' => 'PointOfSaleUser.pointOfSaleId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'User.name',
                'default' => true,
                'foreignKey' => 'PointOfSaleUser.userId',
                'update' => false,
                'save' => false,
            ],
            'email' => [
                'name' => 'Email',
                'type' => 'string',
                'sql' => 'User.email',
                'default' => true,
                'foreignKey' => 'PointOfSaleUser.userId',
                'update' => false,
                'save' => false,
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
            'isActive' => [
                'name' => 'Estado',
                'type' => 'boolean',
                'sql' => 'User.isActive',
                'foreignKey' => 'PointOfSaleUser.userId',
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
                'foreignKey' => 'PointOfSaleUser.userId',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No verificado', 'translation' => 'No verificado', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Verificado', 'translation' => 'Verificado', 'statusColor' => 'green-status'],
                ],
                'update' => false,
                'save' => false,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'User.observations',
                'foreignKey' => 'PointOfSaleUser.userId',
                'update' => false,
                'save' => false,
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'PointOfSaleUser.createdAt',
                'default' => true,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'PointOfSaleUser.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'PointOfSaleUser.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'PointOfSaleUser.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'pointOfSale' => [
                'name' => 'Punto de venta',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => PointOfSale::class,
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
            $user = User::find($this->userId);
            $photo = $user->files()->where('category', 'profileImage')->first();
            return $photo ? $photo->url : 'app/shared/assets/images/avatar-placeholder.png';
        }
        return null;
    }

    /**
     * A PointOfSaleUser belongs to an user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A pointOfSaleUser belongs to a pointOfSale
     *
     * @return BelongsTo
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }

    /**
     * A client has many Orders
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * A client has many tickets
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
