<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Fare
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $sessionId
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string|null $description
 * @property string|null $webDescription
 * @property bool $checkIdentity
 * @property string|null $checkIdentityMessage
 * @property string|null $assistedPointOfSaleMessage
 * @property int|null $minTicketsByOrder
 * @property int|null $maxTicketsByOrder
 * @property int|null $maxTickets
 * @property string|null $restrictionStartDate
 * @property string|null $restrictionEndDate
 * @property bool $associatedToTuPalacio
 * @property bool $isPromotion
 * @property bool $isSeason
 * @property string|null $observations
 * @property string|null $ticketSeasonId
 * @property int|null $oldId
 * @property-read Collection|PointOfSale[] $pointsOfSale
 * @property-read int|null $pointsOfSaleCount
 * @property-read Session $session
 * @property-read Collection|Area[] $sessionAreas
 * @property-read int|null $sessionAreasCount
 * @property-read TicketSeason|null $ticketSeason
 * @method static SavitarBuilder|Fare newModelQuery()
 * @method static SavitarBuilder|Fare newQuery()
 * @method static SavitarBuilder|Fare query()
 * @method static Builder|Fare whereAssistedPointOfSaleMessage($value)
 * @method static Builder|Fare whereAssociatedToTuPalacio($value)
 * @method static Builder|Fare whereCheckIdentity($value)
 * @method static Builder|Fare whereCheckIdentityMessage($value)
 * @method static Builder|Fare whereCreatedAt($value)
 * @method static Builder|Fare whereCreatedBy($value)
 * @method static Builder|Fare whereDeletedAt($value)
 * @method static Builder|Fare whereDeletedBy($value)
 * @method static Builder|Fare whereDescription($value)
 * @method static Builder|Fare whereId($value)
 * @method static Builder|Fare whereIsPromotion($value)
 * @method static Builder|Fare whereIsSeason($value)
 * @method static Builder|Fare whereMaxTickets($value)
 * @method static Builder|Fare whereMaxTicketsByOrder($value)
 * @method static Builder|Fare whereMinTicketsByOrder($value)
 * @method static Builder|Fare whereName($value)
 * @method static Builder|Fare whereObservations($value)
 * @method static Builder|Fare whereOldId($value)
 * @method static Builder|Fare whereRestrictionEndDate($value)
 * @method static Builder|Fare whereRestrictionStartDate($value)
 * @method static Builder|Fare whereSessionId($value)
 * @method static Builder|Fare whereTicketName($value)
 * @method static Builder|Fare whereTicketSeasonId($value)
 * @method static Builder|Fare whereUpdatedAt($value)
 * @method static Builder|Fare whereUpdatedBy($value)
 * @method static Builder|Fare whereWebDescription($value)
 * @method static Builder|Fare whereWebName($value)
 * @mixin Eloquent
 */
class Fare extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Fare.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'Fare.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'Fare.ticketName',
                'default' => true,
            ],
            'description' => [
                'name' => 'Descripción',
                'type' => 'string',
                'sql' => 'Fare.description',
            ],
            'webDescription' => [
                'name' => 'Descripción web',
                'type' => 'string',
                'sql' => 'Fare.webDescription',
            ],
            'checkIdentity' => [
                'name' => 'Comprobación de identidad',
                'type' => 'boolean',
                'sql' => 'Fare.checkIdentity',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'checkIdentityMessage' => [
                'name' => 'Mensaje comprobación identidad',
                'type' => 'string',
                'sql' => 'Fare.checkIdentityMessage',
            ],
            'minTicketsByOrder' => [
                'name' => 'Nº mínimo de entradas por compra',
                'type' => 'int',
                'sql' => 'Fare.minTicketsByOrder',
            ],
            'maxTicketsByOrder' => [
                'name' => 'Nº máximo de entradas por compra',
                'type' => 'int',
                'sql' => 'Fare.maxTicketsByOrder',
            ],
            'maxTickets' => [
                'name' => 'Nº máximo de entradas total (cupo)',
                'type' => 'int',
                'sql' => 'Fare.maxTickets',
            ],
            'restrictionStartDate' => [
                'name' => 'Inicio validez',
                'type' => 'fullDate',
                'sql' => 'Fare.restrictionStartDate',
            ],
            'restrictionEndDate' => [
                'name' => 'Fin validez',
                'type' => 'fullDate',
                'sql' => 'Fare.restrictionEndDate',
            ],
            'associatedToTuPalacio' => [
                'name' => 'Tarifa asociada a Tu Palacio',
                'type' => 'boolean',
                'sql' => 'Fare.associatedToTuPalacio',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'isPromotion' => [
                'name' => 'Tarifa promoción',
                'type' => 'boolean',
                'sql' => 'Fare.isPromotion',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'isSeason' => [
                'name' => 'Tarifa abono',
                'type' => 'boolean',
                'sql' => 'Fare.isSeason',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'No', 'translation' => 'No'],
                    'true' => ['html' => 'Sí', 'translation' => 'Sí'],
                ],
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Fare.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Fare.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Fare.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Fare.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Fare.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'session' => [
                'name' => 'Sesión',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Session::class,
            ],
            'ticketSeason' => [
                'name' => 'Abono asociado',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => TicketSeason::class,
            ],
            'sessionAreas' => [
                'name' => 'Áreas',
                'type' => 'relation',
                'relationType' => 'belongsToMany',
                'relatedModelClass' => SessionArea::class,
            ],
            'tickets' => [
                'name' => 'Entradas',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Ticket::class,
            ],
        ],
    ];

    /**
     * A fare belongs to a session
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * A fare belongs to a ticketSeason
     *
     * @return BelongsTo
     */
    public function ticketSeason(): BelongsTo
    {
        return $this->belongsTo(TicketSeason::class);
    }

    /**
     * A fare belongsToMany sessionAreas
     *
     * @return BelongsToMany
     */
    public function sessionAreas(): BelongsToMany
    {
        return $this->belongsToMany(SessionArea::class, 'SessionAreaFare')->using(SessionAreaFare::class)->withPivot([
            'id',
            'isActive',
            'earlyPrice',
            'earlyDistributionPrice',
            'earlyTotalPrice',
            'ticketOfficePrice',
            'ticketOfficeDistributionPrice',
            'ticketOfficeTotalPrice',
            'createdAt',
            'updatedAt',
        ]);
    }

    /**
     * A fare belongsToMany pointOfSale
     *
     * @return BelongsToMany
     */
    public function pointsOfSale(): BelongsToMany
    {
        return $this->belongsToMany(PointOfSale::class)->using(FarePointOfSale::class)->withPivot([
            'maximumTicketsToSell',
            'createdAt',
            'updatedAt',
        ]);
    }
}
