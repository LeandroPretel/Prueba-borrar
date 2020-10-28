<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\PointOfSaleSession. The pointsOfSale where the session CANT be sold
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $pointOfSaleId
 * @property string $sessionId
 * @property-read User|null $createdByUser
 * @property-read PointOfSale $pointOfSale
 * @property-read Session $session
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|PointOfSaleSession newModelQuery()
 * @method static SavitarBuilder|PointOfSaleSession newQuery()
 * @method static SavitarBuilder|PointOfSaleSession query()
 * @method static Builder|PointOfSaleSession whereCreatedAt($value)
 * @method static Builder|PointOfSaleSession whereCreatedBy($value)
 * @method static Builder|PointOfSaleSession whereId($value)
 * @method static Builder|PointOfSaleSession wherePointOfSaleId($value)
 * @method static Builder|PointOfSaleSession whereSessionId($value)
 * @method static Builder|PointOfSaleSession whereUpdatedAt($value)
 * @method static Builder|PointOfSaleSession whereUpdatedBy($value)
 * @mixin Eloquent
 */
class PointOfSaleSession extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * @return BelongsTo
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }
}
