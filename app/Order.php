<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;
use Savitar\Models\Traits\AccountRelatable;

/**
 * App\Order
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $accountId
 * @property string $pointOfSaleId
 * @property string|null $clientId
 * @property string|null $discountId
 * @property string $locator
 * @property string $type
 * @property float $amount
 * @property float $amountPaid
 * @property float $amountPending
 * @property string $status
 * @property string $via
 * @property string|null $observations
 * @property-read Account $account
 * @property-read Client|null $client
 * @property-read Discount|null $discount
 * @property-read Collection|Ticket[] $goodTickets
 * @property-read int|null $goodTicketsCount
 * @property-read Collection|PaymentAttempt[] $paymentAttempts
 * @property-read int|null $paymentAttemptsCount
 * @property-read PointOfSale $pointOfSale
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @property-read TicketSeasonOrder|null $ticketSeasonOrder
 * @property-read TicketVoucherOrder|null $ticketVoucherOrder
 * @property-read Collection|Ticket[] $tickets
 * @property-read int|null $ticketsCount
 * @method static SavitarBuilder|Order newModelQuery()
 * @method static SavitarBuilder|Order newQuery()
 * @method static SavitarBuilder|Order query()
 * @method static Builder|Order whereAccountId($value)
 * @method static Builder|Order whereAmount($value)
 * @method static Builder|Order whereAmountPaid($value)
 * @method static Builder|Order whereAmountPending($value)
 * @method static Builder|Order whereClientId($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereCreatedBy($value)
 * @method static Builder|Order whereDeletedAt($value)
 * @method static Builder|Order whereDeletedBy($value)
 * @method static Builder|Order whereDiscountId($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereLocator($value)
 * @method static Builder|Order whereVia($value)
 * @method static Builder|Order whereObservations($value)
 * @method static Builder|Order wherePointOfSaleId($value)
 * @method static Builder|Order whereStatus($value)
 * @method static Builder|Order whereType($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order whereUpdatedBy($value)
 * @mixin Eloquent
 */
class Order extends SavitarModel
{
    use AccountRelatable;

    protected $modelDefinition = [
        'visibleAttributes' => [
            'locator' => [
                'name' => 'Localizador',
                'type' => 'string',
                'sql' => 'Order.locator',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'type' => [
                'name' => 'Tipo',
                'type' => 'array',
                'sql' => 'Order.type',
                'possibleValues' => ['normal', 'season', 'voucher', 'hard-ticket', 'home-ticket', 'invitation'],
                'configuration' => [
                    'normal' => ['html' => 'Normal', 'translation' => 'Normal'],
                    'season' => ['html' => 'Abono', 'translation' => 'Abono'],
                    'voucher' => ['html' => 'Bono', 'translation' => 'Bono'],
                    'hard-ticket' => ['html' => 'Hard Ticket', 'translation' => 'Hard Ticket'],
                    'home-ticket' => ['html' => 'Home Ticket', 'translation' => 'Home Ticket'],
                    'invitation' => ['html' => 'Invitación', 'translation' => 'Invitación'],
                ],
                'default' => true,
            ],
            'via' => [
                'name' => 'Vía',
                'type' => 'array',
                'sql' => 'Order.via',
                'foreignKey' => 'Order.via',
                'possibleValues' => ['web', 'automatic', 'assisted', 'phone'],
                'configuration' => [
                    'web' => ['html' => 'Web', 'translation' => 'Web'],
                    'automatic' => ['html' => 'Automática', 'translation' => 'Automática'],
                    'assisted' => ['html' => 'Asistida', 'translation' => 'Asistida'],
                    'phone' => ['html' => 'Telefónica', 'translation' => 'Telefónica'],
                ],
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'pointOfSaleName' => [
                'name' => 'Punto de venta',
                'type' => 'string',
                'sql' => 'PointOfSale.name',
                'foreignKey' => 'Order.pointOfSaleId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'clientName' => [
                'name' => 'Cliente',
                'type' => 'string',
                'sql' => 'Client.name',
                'foreignKey' => 'Order.clientId',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'clientNif' => [
                'name' => 'DNI Cliente',
                'type' => 'string',
                'sql' => 'Client.nif',
                'foreignKey' => 'Order.clientId',
                'update' => false,
                'save' => false,
            ],
            /**
            'goodTicketsCount' => [
                'name' => 'Cantidad de entradas',
                'type' => 'count',
                'countRelation' => 'goodTickets',
                'default' => true,
            ],
             */
            'amount' => [
                'name' => 'Importe',
                'type' => 'money',
                'sql' => 'Order.amount',
                'default' => true,
            ],
            'amountPaid' => [
                'name' => 'Pagado',
                'type' => 'money',
                'sql' => 'Order.amountPaid',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'amountPending' => [
                'name' => 'Pendiente',
                'type' => 'money',
                'sql' => 'Order.amountPending',
                'update' => false,
                'save' => false,
                'default' => true,
            ],
            'status' => [
                'name' => 'Estado',
                'type' => 'array',
                'sql' => 'Order.status',
                'default' => true,
                'possibleValues' => ['pending', 'paid', 'partially-paid', 'unfinished'],
                'configuration' => [
                    'pending' => ['html' => 'En proceso', 'translation' => 'En proceso', 'statusColor' => 'orange-status'],
                    'paid' => ['html' => 'Pagado', 'translation' => 'Pagado', 'statusColor' => 'green-status'],
                    'partially-paid' => ['html' => 'Parcialmente pagado', 'translation' => 'Parcialmente pagado', 'statusColor' => 'purple-status'],
                    'unfinished' => ['html' => 'Sin finalizar', 'translation' => 'Sin finalizar', 'statusColor' => 'red-status'],
                ],
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Order.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Order.createdAt',
                'default' => true,
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Order.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Order.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Order.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'account' => [
                'name' => 'Promotor',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Account::class,
            ],
            'pointOfSale' => [
                'name' => 'Punto de venta',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => PointOfSale::class,
            ],
            'client' => [
                'name' => 'Cliente',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Client::class,
            ],
            'discount' => [
                'name' => 'Descuento/Promoción',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Discount::class,
            ],
            'tickets' => [
                'name' => 'Entradas',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Ticket::class,
            ],
            'paymentAttempts' => [
                'name' => 'Intentos de pago',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => PaymentAttempt::class,
            ],
        ],
    ];

    /**
     * An order has many paymentAttempts.
     *
     * @return HasMany
     */
    public function paymentAttempts(): HasMany
    {
        return $this->hasMany(PaymentAttempt::class)->orderBy('createdAt', 'desc');
    }

    /**
     * An order belongs to a client.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    /**
     * An order belongs to a pointOfSale.
     *
     * @return BelongsTo
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class)->withTrashed();
    }

    /**
     * An order hasMany sessions.
     *
     * @return HasManyThrough
     */
    public function sessions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Session::class,
            Ticket::class,
            'sessionId'

        )->withTrashed();
    }

    /**
     * An order belongs to a discount.
     *
     * @return BelongsTo
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class)->withTrashed();
    }

    /**
     * An order has many tickets.
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class)->withTrashed();
    }

    /**
     * An order has many tickets.
     *
     * @return HasMany
     */
    public function goodTickets(): HasMany
    {
        return $this->hasMany(Ticket::class)
            ->whereNull('orderReturnId');
    }

    /**
     * An order may have a ticket season order related.
     *
     * @return HasOne
     */
    public function ticketSeasonOrder(): HasOne
    {
        return $this->hasOne(TicketSeasonOrder::class)->withTrashed();
    }

    /**
     * An order may have a ticket season order related.
     *
     * @return HasOne
     */
    public function ticketVoucherOrder(): HasOne
    {
        return $this->hasOne(TicketVoucherOrder::class)->withTrashed();
    }
}
