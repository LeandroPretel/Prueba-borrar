<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\PlacePointOfSale
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $pointOfSaleId
 * @property string $placeId
 * @property-read User|null $createdByUser
 * @property-read PointOfSale $pointOfSale
 * @property-read Place $place
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|PlacePointOfSale newModelQuery()
 * @method static SavitarBuilder|PlacePointOfSale newQuery()
 * @method static SavitarBuilder|PlacePointOfSale query()
 * @method static Builder|PlacePointOfSale whereCreatedAt($value)
 * @method static Builder|PlacePointOfSale whereCreatedBy($value)
 * @method static Builder|PlacePointOfSale whereId($value)
 * @method static Builder|PlacePointOfSale wherePointOfSaleId($value)
 * @method static Builder|PlacePointOfSale whereSessionId($value)
 * @method static Builder|PlacePointOfSale whereUpdatedAt($value)
 * @method static Builder|PlacePointOfSale whereUpdatedBy($value)
 * @method static Builder|PlacePointOfSale wherePlaceId($value)
 * @mixin Eloquent
 */
class PlacePointOfSale extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * @return BelongsTo
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }

}
