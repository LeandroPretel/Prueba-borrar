<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\TicketSeasonSession
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $ticketSeasonId
 * @property string $sessionId
 * @property bool $required
 * @property-read Session $session
 * @property-read TicketSeason $ticketSeason
 * @method static SavitarBuilder|TicketSeasonSession newModelQuery()
 * @method static SavitarBuilder|TicketSeasonSession newQuery()
 * @method static SavitarBuilder|TicketSeasonSession query()
 * @method static Builder|TicketSeasonSession whereCreatedAt($value)
 * @method static Builder|TicketSeasonSession whereCreatedBy($value)
 * @method static Builder|TicketSeasonSession whereId($value)
 * @method static Builder|TicketSeasonSession whereSessionId($value)
 * @method static Builder|TicketSeasonSession whereTicketSeasonId($value)
 * @method static Builder|TicketSeasonSession whereUpdatedAt($value)
 * @method static Builder|TicketSeasonSession whereUpdatedBy($value)
 * @method static Builder|TicketSeasonSession whereRequired($value)
 * @mixin Eloquent
 */
class TicketSeasonSession extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function ticketSeason(): BelongsTo
    {
        return $this->belongsTo(TicketSeason::class);
    }

    /**
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
}
