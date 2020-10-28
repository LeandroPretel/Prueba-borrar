<?php

namespace App;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;
use Savitar\Models\Traits\AccountRelatable;

/**
 * App\TicketSeason
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $accountId
 * @property string $placeId
 * @property string $spaceId
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string $type
 * @property bool $isActive
 * @property string|null $description
 * @property int|null $minSessions
 * @property int|null $maxSessions
 * @property bool $renovationsEnabled
 * @property string|null $renovationStartDate
 * @property string|null $renovationEndDate
 * @property bool $changesEnabled
 * @property string|null $changesStartDate
 * @property string|null $changesEndDate
 * @property bool $newSalesEnabled
 * @property string|null $newSalesStartDate
 * @property string|null $newSalesEndDate
 * @property bool $shippingEnabled
 * @property string|null $shippingStartDate
 * @property string|null $shippingEndDate
 * @property bool $printingEnabled
 * @property string|null $observations
 * @property string|null $ticketSeasonGroupId
 * @property int|null $oldId
 * @property-read Account $account
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read mixed|string $mainImageUrl
 * @property-read Place $place
 * @property-read Collection|PointOfSale[] $pointsOfSale
 * @property-read int|null $pointsOfSaleCount
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @property-read Space $space
 * @property-read TicketSeasonGroup|null $ticketSeasonGroup
 * @property-read Collection|TicketSeasonOrder[] $ticketSeasonOrders
 * @property-read int|null $ticketSeasonOrdersCount
 * @property-read TicketSeasonPrintModel|null $ticketSeasonPrintModel
 * @method static SavitarBuilder|TicketSeason newModelQuery()
 * @method static SavitarBuilder|TicketSeason newQuery()
 * @method static SavitarBuilder|TicketSeason query()
 * @method static Builder|TicketSeason whereAccountId($value)
 * @method static Builder|TicketSeason whereIsActive($value)
 * @method static Builder|TicketSeason whereChangesEnabled($value)
 * @method static Builder|TicketSeason whereChangesEndDate($value)
 * @method static Builder|TicketSeason whereChangesStartDate($value)
 * @method static Builder|TicketSeason whereCreatedAt($value)
 * @method static Builder|TicketSeason whereCreatedBy($value)
 * @method static Builder|TicketSeason whereDeletedAt($value)
 * @method static Builder|TicketSeason whereDeletedBy($value)
 * @method static Builder|TicketSeason whereDescription($value)
 * @method static Builder|TicketSeason whereId($value)
 * @method static Builder|TicketSeason whereMaxSessions($value)
 * @method static Builder|TicketSeason whereMinSessions($value)
 * @method static Builder|TicketSeason whereName($value)
 * @method static Builder|TicketSeason whereNewSalesEnabled($value)
 * @method static Builder|TicketSeason whereNewSalesEndDate($value)
 * @method static Builder|TicketSeason whereNewSalesStartDate($value)
 * @method static Builder|TicketSeason whereObservations($value)
 * @method static Builder|TicketSeason wherePlaceId($value)
 * @method static Builder|TicketSeason wherePrintingEnabled($value)
 * @method static Builder|TicketSeason whereRenovationEndDate($value)
 * @method static Builder|TicketSeason whereRenovationStartDate($value)
 * @method static Builder|TicketSeason whereRenovationsEnabled($value)
 * @method static Builder|TicketSeason whereShippingEnabled($value)
 * @method static Builder|TicketSeason whereShippingEndDate($value)
 * @method static Builder|TicketSeason whereShippingStartDate($value)
 * @method static Builder|TicketSeason whereSpaceId($value)
 * @method static Builder|TicketSeason whereTicketName($value)
 * @method static Builder|TicketSeason whereTicketSeasonGroupId($value)
 * @method static Builder|TicketSeason whereType($value)
 * @method static Builder|TicketSeason whereUpdatedAt($value)
 * @method static Builder|TicketSeason whereUpdatedBy($value)
 * @method static Builder|TicketSeason whereWebName($value)
 * @method static Builder|TicketSeason whereOldId($value)
 * @mixin Eloquent
 */
class TicketSeason extends SavitarModel
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
                'sql' => 'TicketSeason.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'TicketSeason.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'TicketSeason.ticketName',
                'default' => true,
            ],
            'isActive' => [
                'name' => 'Activo',
                'type' => 'boolean',
                'sql' => 'TicketSeason.isActive',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
                'default' => true,
            ],
            'type' => [
                'name' => 'Tipo',
                'type' => 'array',
                'sql' => 'TicketSeason.type',
                'possibleValues' => ['fixed', 'combined'],
                'configuration' => [
                    'fixed' => ['html' => 'Fijo', 'translation' => 'Fijo'],
                    'combined' => ['html' => 'Combinado', 'translation' => 'Combinado'],
                ],
            ],
            'description' => [
                'name' => 'Descripción',
                'type' => 'string',
                'sql' => 'TicketSeason.description',
            ],
            'placeName' => [
                'name' => 'Recinto',
                'type' => 'string',
                'sql' => 'Place.name',
                'foreignKey' => 'TicketSeason.placeId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'spaceName' => [
                'name' => 'Aforo',
                'type' => 'string',
                'sql' => 'Space.name',
                'foreignKey' => 'TicketSeason.spaceId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'ticketSeasonGroupName' => [
                'name' => 'Grupo abonados',
                'type' => 'string',
                'sql' => 'TicketSeasonGroup.name',
                'foreignKey' => 'TicketSeason.ticketSeasonGroupId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'minSessions' => [
                'name' => 'Mínimo de sesiones',
                'type' => 'int',
                'sql' => 'TicketSeason.minSessions',
            ],
            'maxSessions' => [
                'name' => 'Máximo de sesiones',
                'type' => 'int',
                'sql' => 'TicketSeason.maxSessions',
            ],
            'renovationsEnabled' => [
                'name' => 'Renovaciones',
                'type' => 'boolean',
                'sql' => 'TicketSeason.renovationsEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'renovationStartDate' => [
                'name' => 'Inicio fase de renovación',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.renovationStartDate',
            ],
            'renovationEndDate' => [
                'name' => 'Fin fase de renovación',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.renovationEndDate',
            ],
            'changesEnabled' => [
                'name' => 'Envío',
                'type' => 'boolean',
                'sql' => 'TicketSeason.changesEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'changesStartDate' => [
                'name' => 'Inicio fase de cambios',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.changesStartDate',
            ],
            'changesEndDate' => [
                'name' => 'Fin fase de cambios',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.changesEndDate',
            ],
            'newSalesEnabled' => [
                'name' => 'Nuevos abonos',
                'type' => 'boolean',
                'sql' => 'TicketSeason.newSalesEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'newSalesStartDate' => [
                'name' => 'Inicio fase de nuevos abonos',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.newSalesStartDate',
            ],
            'newSalesEndDate' => [
                'name' => 'Fin fase de nuevos abonos',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.newSalesEndDate',
            ],
            'shippingEnabled' => [
                'name' => 'Envío',
                'type' => 'boolean',
                'sql' => 'TicketSeason.shippingEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'shippingStartDate' => [
                'name' => 'Inicio envíos',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.shippingStartDate',
            ],
            'shippingEndDate' => [
                'name' => 'Fin envíos',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.shippingEndDate',
            ],
            'printingEnabled' => [
                'name' => 'Impresión de tarjeta de abonado',
                'type' => 'boolean',
                'sql' => 'TicketSeason.printingEnabled',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'TicketSeason.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'TicketSeason.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'TicketSeason.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'TicketSeason.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'account' => [
                'name' => 'Cuenta',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Account::class,
            ],
            'place' => [
                'name' => 'Recinto',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Place::class,
            ],
            'space' => [
                'name' => 'Aforo',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Space::class,
            ],
            'ticketSeasonGroup' => [
                'name' => 'Grupo de abonados',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => TicketSeasonGroup::class,
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
                'name' => 'Ventas de abono',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => TicketSeasonOrder::class,
            ],
            'ticketSeasonPrintModel' => [
                'name' => 'Modelo de tarjeta abonado',
                'type' => 'relation',
                'relationType' => 'hasOne',
                'relatedModelClass' => TicketSeasonPrintModel::class,
            ],
        ],
    ];

    protected $appends = ['mainImageUrl'];

    protected $filesInputKeys = ['files', 'mainImage'];

    /**
     * The main image of the ticketSeason.
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
     * A ticketSeason belongs to a place
     *
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class)->withTrashed();
    }

    /**
     * A ticketSeason belongs to a space
     *
     * @return BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class)->withTrashed();
    }

    /**
     * A ticketSeason belongs to a ticketSeasonGroup
     *
     * @return BelongsTo
     */
    public function ticketSeasonGroup(): BelongsTo
    {
        return $this->belongsTo(TicketSeasonGroup::class)->withTrashed();
    }

    /**
     * A ticketSeason belongs to many sessions
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'TicketSeasonSession')
            ->using(TicketSeasonSession::class)
            ->withTimestamps();
    }

    /**
     * A session has many fares
     *
     * @return HasMany
     */
    public function fares(): HasMany
    {
        return $this->hasMany(Fare::class)->orderBy('createdAt');
    }

    /**
     * A ticketSeason belongsToMany pointOfSale
     *
     * @return BelongsToMany
     */
    public function pointsOfSale(): BelongsToMany
    {
        return $this->belongsToMany(PointOfSale::class, 'TicketSeasonPointOfSale')
            ->using(TicketSeasonPointOfSale::class)
            ->withPivot([
                'createdAt',
                'updatedAt',
            ]);
    }

    /**
     * A ticketSeason HasMany ticketSeasonOrders
     *
     * @return HasMany
     */
    public function ticketSeasonOrders(): HasMany
    {
        return $this->hasMany(TicketSeasonOrder::class);
    }

    /**
     * A ticketSeason has one ticketSeasonPrintModel
     *
     * @return HasOne
     */
    public function ticketSeasonPrintModel(): HasOne
    {
        return $this->hasOne(TicketSeasonPrintModel::class);
    }
}
