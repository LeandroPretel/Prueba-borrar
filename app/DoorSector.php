<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\DoorSector
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $doorId
 * @property string $sectorId
 * @property-read Door $door
 * @property-read Sector $sector
 * @method static SavitarBuilder|DoorSector newModelQuery()
 * @method static SavitarBuilder|DoorSector newQuery()
 * @method static SavitarBuilder|DoorSector query()
 * @method static Builder|DoorSector whereCreatedAt($value)
 * @method static Builder|DoorSector whereCreatedBy($value)
 * @method static Builder|DoorSector whereDoorId($value)
 * @method static Builder|DoorSector whereId($value)
 * @method static Builder|DoorSector whereSectorId($value)
 * @method static Builder|DoorSector whereUpdatedAt($value)
 * @method static Builder|DoorSector whereUpdatedBy($value)
 * @mixin Eloquent
 */
class DoorSector extends SavitarPivotModel
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
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }
}
