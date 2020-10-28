<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\DoorSeat
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $doorId
 * @property string $seatId
 * @property-read Door $door
 * @property-read Seat $seat
 * @method static SavitarBuilder|DoorSeat newModelQuery()
 * @method static SavitarBuilder|DoorSeat newQuery()
 * @method static SavitarBuilder|DoorSeat query()
 * @method static Builder|DoorSeat whereCreatedAt($value)
 * @method static Builder|DoorSeat whereCreatedBy($value)
 * @method static Builder|DoorSeat whereDoorId($value)
 * @method static Builder|DoorSeat whereId($value)
 * @method static Builder|DoorSeat whereSeatId($value)
 * @method static Builder|DoorSeat whereUpdatedAt($value)
 * @method static Builder|DoorSeat whereUpdatedBy($value)
 * @mixin Eloquent
 */
class DoorSeat extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function door(): BelongsTo
    {
        return $this->belongsTo(Door::class);
    }

    /**
     * @return BelongsTo
     */
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
}
