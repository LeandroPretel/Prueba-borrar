<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\SessionDoorSessionSector
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $sessionDoorId
 * @property string $sessionSectorId
 * @property-read SessionDoor $sessionDoor
 * @property-read SessionSector $sessionSector
 * @method static SavitarBuilder|SessionDoorSessionSector newModelQuery()
 * @method static SavitarBuilder|SessionDoorSessionSector newQuery()
 * @method static SavitarBuilder|SessionDoorSessionSector query()
 * @method static Builder|SessionDoorSessionSector whereCreatedAt($value)
 * @method static Builder|SessionDoorSessionSector whereCreatedBy($value)
 * @method static Builder|SessionDoorSessionSector whereId($value)
 * @method static Builder|SessionDoorSessionSector whereSessionDoorId($value)
 * @method static Builder|SessionDoorSessionSector whereSessionSectorId($value)
 * @method static Builder|SessionDoorSessionSector whereUpdatedAt($value)
 * @method static Builder|SessionDoorSessionSector whereUpdatedBy($value)
 * @mixin Eloquent
 */
class SessionDoorSessionSector extends SavitarPivotModel
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
    public function sessionSector(): BelongsTo
    {
        return $this->belongsTo(SessionSector::class);
    }
}
