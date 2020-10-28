<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\TicketSeasonOrderSession
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $ticketSeasonOrderId
 * @property string $sessionId
 * @property-read Session $session
 * @property-read TicketSeasonOrder $ticketSeasonOrder
 * @method static SavitarBuilder|TicketSeasonOrderSession newModelQuery()
 * @method static SavitarBuilder|TicketSeasonOrderSession newQuery()
 * @method static SavitarBuilder|TicketSeasonOrderSession query()
 * @method static Builder|TicketSeasonOrderSession whereCreatedAt($value)
 * @method static Builder|TicketSeasonOrderSession whereCreatedBy($value)
 * @method static Builder|TicketSeasonOrderSession whereId($value)
 * @method static Builder|TicketSeasonOrderSession whereSessionId($value)
 * @method static Builder|TicketSeasonOrderSession whereTicketSeasonOrderId($value)
 * @method static Builder|TicketSeasonOrderSession whereUpdatedAt($value)
 * @method static Builder|TicketSeasonOrderSession whereUpdatedBy($value)
 * @mixin Eloquent
 * @property bool $required
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketSeasonOrderSession whereRequired($value)
 */
class TicketSeasonOrderSession extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function ticketSeasonOrder(): BelongsTo
    {
        return $this->belongsTo(TicketSeasonOrder::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class)->withTrashed();
    }
}
