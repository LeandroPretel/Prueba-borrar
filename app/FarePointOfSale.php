<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\FarePointOfSale
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $fareId
 * @property string $pointOfSaleId
 * @property int|null $maximumTicketsToSell
 * @property-read User|null $createdByUser
 * @property-read Fare $fare
 * @property-read PointOfSale $pointOfSale
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|FarePointOfSale newModelQuery()
 * @method static SavitarBuilder|FarePointOfSale newQuery()
 * @method static SavitarBuilder|FarePointOfSale query()
 * @method static Builder|FarePointOfSale whereCreatedAt($value)
 * @method static Builder|FarePointOfSale whereCreatedBy($value)
 * @method static Builder|FarePointOfSale whereFareId($value)
 * @method static Builder|FarePointOfSale whereId($value)
 * @method static Builder|FarePointOfSale whereMaximumTicketsToSell($value)
 * @method static Builder|FarePointOfSale wherePointOfSaleId($value)
 * @method static Builder|FarePointOfSale whereUpdatedAt($value)
 * @method static Builder|FarePointOfSale whereUpdatedBy($value)
 * @mixin Eloquent
 */
class FarePointOfSale extends SavitarPivotModel
{
    protected $fillable = [
        'maximumTicketsToSell',
    ];

    /**
     * @return BelongsTo
     */
    public function fare(): BelongsTo
    {
        return $this->belongsTo(Fare::class);
    }

    /**
     * @return BelongsTo
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }
}
