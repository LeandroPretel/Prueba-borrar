<?php

namespace App;

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
 * App\TicketVoucher
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $accountId
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property bool $isActive
 * @property string|null $description
 * @property string|null $observations
 * @property int|null $minSessions
 * @property int|null $maxSessions
 * @property-read Account $account
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read mixed|string $mainImageUrl
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @property-read Collection|TicketVoucherOrder[] $ticketVoucherOrders
 * @property-read int|null $ticketVoucherOrdersCount
 * @property-read Collection|PointOfSale[] $pointsOfSale
 * @property-read int|null $pointsOfSaleCount
 * @method static SavitarBuilder|TicketVoucher newModelQuery()
 * @method static SavitarBuilder|TicketVoucher newQuery()
 * @method static SavitarBuilder|TicketVoucher query()
 * @method static Builder|TicketVoucher whereAccountId($value)
 * @method static Builder|TicketVoucher whereCreatedAt($value)
 * @method static Builder|TicketVoucher whereCreatedBy($value)
 * @method static Builder|TicketVoucher whereDeletedAt($value)
 * @method static Builder|TicketVoucher whereDeletedBy($value)
 * @method static Builder|TicketVoucher whereDescription($value)
 * @method static Builder|TicketVoucher whereId($value)
 * @method static Builder|TicketVoucher whereIsActive($value)
 * @method static Builder|TicketVoucher whereMaxSessions($value)
 * @method static Builder|TicketVoucher whereMinSessions($value)
 * @method static Builder|TicketVoucher whereName($value)
 * @method static Builder|TicketVoucher whereTicketName($value)
 * @method static Builder|TicketVoucher whereUpdatedAt($value)
 * @method static Builder|TicketVoucher whereUpdatedBy($value)
 * @method static Builder|TicketVoucher whereWebName($value)
 * @method static Builder|TicketVoucher whereObservations($value)
 * @mixin Eloquent
 */
class TicketVoucher extends SavitarModel
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
                'sql' => 'TicketVoucher.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'TicketVoucher.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'TicketVoucher.ticketName',
                'default' => true,
            ],
            'isActive' => [
                'name' => 'Activo',
                'type' => 'boolean',
                'sql' => 'TicketVoucher.isActive',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
                'default' => true,
            ],
            'description' => [
                'name' => 'Descripción',
                'type' => 'string',
                'sql' => 'TicketVoucher.description',
            ],
            'minSessions' => [
                'name' => 'Mínimo de sesiones',
                'type' => 'int',
                'sql' => 'TicketVoucher.minSessions',
            ],
            'maxSessions' => [
                'name' => 'Máximo de sesiones',
                'type' => 'int',
                'sql' => 'TicketVoucher.maxSessions',
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'TicketVoucher.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'TicketVoucher.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'TicketVoucher.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'TicketVoucher.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'TicketVoucher.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'account' => [
                'name' => 'Cuenta',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Account::class,
            ],
            'pointsOfSale' => [
                'name' => 'Puntos de venta',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => PointOfSale::class,
            ],
            'sessions' => [
                'name' => 'Sesiones',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => Session::class,
            ],
            'ticketSeasonOrders' => [
                'name' => 'Ventas de bono',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => TicketVoucherOrder::class,
            ],
        ],
    ];

    protected $appends = ['mainImageUrl'];

    protected $filesInputKeys = ['files', 'mainImage'];

    /**
     * The main image of the ticketVoucher
     *
     * @return mixed|string
     * @throws Exception
     */
    public function getMainImageUrlAttribute()
    {
        $photo = $this->files()->where('category', 'mainImage')->first();
        return $photo ? $photo->url : 'assets/event-placeholder.svg';
    }

    /**
     * A ticketVoucher hasMany ticketVoucherOrders
     *
     * @return HasMany
     */
    public function ticketVoucherOrders(): HasMany
    {
        return $this->hasMany(TicketVoucherOrder::class);
    }

    /**
     * A ticketVoucher belongs to many sessions
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'TicketVoucherSession')
            ->using(TicketVoucherSession::class)
            ->withTimestamps();
    }

    /**
     * A ticketVoucher belongsToMany pointOfSale
     *
     * @return BelongsToMany
     */
    public function pointsOfSale(): BelongsToMany
    {
        return $this->belongsToMany(PointOfSale::class, 'TicketVoucherPointOfSale')
            ->using(TicketVoucherPointOfSale::class)
            ->withPivot([
                'createdAt',
                'updatedAt',
            ]);
    }
}
