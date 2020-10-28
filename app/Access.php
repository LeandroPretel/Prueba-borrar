<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBaseModel;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\Traits\UsesUUID;

/**
 * App\Access
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $ticketId
 * @property string $sessionId
 * @property string $sessionSeatId
 * @property string|null $clientId
 * @property string $status
 * @property-read Session $session
 * @property-read Ticket $ticket
 * @property-read SessionSeat $sessionSeat
 * @property-read Client $client
 * @method static SavitarBuilder|Access newModelQuery()
 * @method static SavitarBuilder|Access newQuery()
 * @method static SavitarBuilder|Access query()
 * @method static Builder|Access whereCreatedAt($value)
 * @method static Builder|Access whereCreatedBy($value)
 * @method static Builder|Access whereId($value)
 * @method static Builder|Access whereSessionId($value)
 * @method static Builder|Access whereStatus($value)
 * @method static Builder|Access whereTicketId($value)
 * @method static Builder|Access whereSessionSeatId($value)
 * @method static Builder|Access whereClientId($value)
 * @method static Builder|Access whereUpdatedAt($value)
 * @method static Builder|Access whereUpdatedBy($value)
 * @mixin Eloquent
 */
class Access extends SavitarBaseModel
{
    use UsesUUID;

    protected $modelDefinition = [
        'visibleAttributes' => [
            'status' => [
                'name' => 'Tipo',
                'type' => 'array',
                'sql' => 'Access.status',
                'possibleValues' => [false, true],
                'configuration' => [
                    'error' => ['html' => 'Error', 'translation' => 'Error'],
                    'successful' => ['html' => 'Acceso', 'translation' => 'Acceso'],
                    'out' => ['html' => 'Salida', 'translation' => 'Salida'],
                ],
            ],
            'createdAt' => [
                'name' => 'Fecha de acceso',
                'type' => 'fullDate',
                'sql' => 'Access.createdAt',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Access.updatedAt',
            ],
        ],
        'shadowAttributes' => [
            'id' => [
                'name' => 'UUID',
                'validation' => 'uuid|required',
                'type' => 'string',
                'update' => false,
                'save' => false,
            ],
            'session' => [
                'name' => 'Sesión',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Session::class,
            ],
            'sessionSeat' => [
                'name' => 'Asiento',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => SessionSeat::class,
            ],
            'ticket' => [
                'name' => 'Entrada',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Ticket::class,
            ],
            'client' => [
                'name' => 'Cliente',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Client::class,
            ],
        ],
    ];

    public $controlVariables = false;

    protected $hidden = [
        'ticketId',
        'sessionSeatId',
        'clientId',
        'sessionId',
        'createdBy',
        'updatedBy',
    ];

    /**
     * An access belongs to a session.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * An access belongs to a sessionSeat.
     *
     * @return BelongsTo
     */
    public function sessionSeat(): BelongsTo
    {
        return $this->belongsTo(SessionSeat::class);
    }

    /**
     * An access belongs to a client.
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * An access belongs to a ticket.
     *
     * @return BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
