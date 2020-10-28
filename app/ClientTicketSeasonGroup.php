<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\ClientTicketSeasonGroup
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $clientId
 * @property string $ticketSeasonGroupId
 * @property-read Client $client
 * @property-read TicketSeasonGroup $ticketSeasonGroup
 * @method static SavitarBuilder|ClientTicketSeasonGroup newModelQuery()
 * @method static SavitarBuilder|ClientTicketSeasonGroup newQuery()
 * @method static SavitarBuilder|ClientTicketSeasonGroup query()
 * @method static Builder|ClientTicketSeasonGroup whereClientId($value)
 * @method static Builder|ClientTicketSeasonGroup whereCreatedAt($value)
 * @method static Builder|ClientTicketSeasonGroup whereCreatedBy($value)
 * @method static Builder|ClientTicketSeasonGroup whereId($value)
 * @method static Builder|ClientTicketSeasonGroup whereTicketSeasonGroupId($value)
 * @method static Builder|ClientTicketSeasonGroup whereUpdatedAt($value)
 * @method static Builder|ClientTicketSeasonGroup whereUpdatedBy($value)
 * @mixin Eloquent
 */
class ClientTicketSeasonGroup extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function ticketSeasonGroup(): BelongsTo
    {
        return $this->belongsTo(TicketSeasonGroup::class)->withTrashed();
    }
}
