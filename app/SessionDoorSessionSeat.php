<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\SessionDoorSessionSeat
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $sessionDoorId
 * @property string $sessionSeatId
 * @property-read SessionDoor $sessionDoor
 * @property-read SessionSeat $sessionSeat
 * @method static SavitarBuilder|SessionDoorSessionSeat newModelQuery()
 * @method static SavitarBuilder|SessionDoorSessionSeat newQuery()
 * @method static SavitarBuilder|SessionDoorSessionSeat query()
 * @method static Builder|SessionDoorSessionSeat whereCreatedAt($value)
 * @method static Builder|SessionDoorSessionSeat whereCreatedBy($value)
 * @method static Builder|SessionDoorSessionSeat whereId($value)
 * @method static Builder|SessionDoorSessionSeat whereSessionDoorId($value)
 * @method static Builder|SessionDoorSessionSeat whereSessionSeatId($value)
 * @method static Builder|SessionDoorSessionSeat whereUpdatedAt($value)
 * @method static Builder|SessionDoorSessionSeat whereUpdatedBy($value)
 * @mixin Eloquent
 */
class SessionDoorSessionSeat extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function sessionDoor(): BelongsTo
    {
        return $this->belongsTo(SessionDoor::class);
    }

    /**
     * @return BelongsTo
     */
    public function sessionSeat(): BelongsTo
    {
        return $this->belongsTo(SessionSeat::class);
    }
}
