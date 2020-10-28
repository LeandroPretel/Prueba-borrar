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
 * App\TicketSeasonOrder
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $ticketSeasonId
 * @property string $orderId
 * @property-read Order $order
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @property-read TicketSeason $ticketSeason
 * @method static SavitarBuilder|TicketSeasonOrder newModelQuery()
 * @method static SavitarBuilder|TicketSeasonOrder newQuery()
 * @method static SavitarBuilder|TicketSeasonOrder query()
 * @method static Builder|TicketSeasonOrder whereCreatedAt($value)
 * @method static Builder|TicketSeasonOrder whereCreatedBy($value)
 * @method static Builder|TicketSeasonOrder whereDeletedAt($value)
 * @method static Builder|TicketSeasonOrder whereDeletedBy($value)
 * @method static Builder|TicketSeasonOrder whereId($value)
 * @method static Builder|TicketSeasonOrder whereOrderId($value)
 * @method static Builder|TicketSeasonOrder whereTicketSeasonId($value)
 * @method static Builder|TicketSeasonOrder whereUpdatedAt($value)
 * @method static Builder|TicketSeasonOrder whereUpdatedBy($value)
 * @mixin Eloquent
 */
class TicketSeasonOrder extends SavitarModel
{
    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function ticketSeason(): BelongsTo
    {
        return $this->belongsTo(TicketSeason::class)->withTrashed();
    }

    /**
     * A ticketSeasonOrder belongs to many sessions
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'TicketSeasonOrderSession')
            ->using(TicketSeasonOrderSession::class)
            ->withTimestamps();
    }
}
