<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Savitar\Models\SavitarPivotModel;

/**
 * App\BrandShow
 *
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $brandId
 * @property string $showId
 * @property bool $associatedToRedentradas
 * @property-read \App\Brand $brand
 * @property-read \App\Show $show
 * @method static \Savitar\Models\SavitarBuilder|\App\BrandShow newModelQuery()
 * @method static \Savitar\Models\SavitarBuilder|\App\BrandShow newQuery()
 * @method static \Savitar\Models\SavitarBuilder|\App\BrandShow query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BrandShow whereAssociatedToRedentradas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BrandShow whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BrandShow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BrandShow whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BrandShow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BrandShow whereShowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BrandShow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BrandShow whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class BrandShow extends SavitarPivotModel
{
    protected $fillable = [
        'associatedToRedentradas'
    ];
    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return BelongsTo
     */
    public function show(): BelongsTo
    {
        return $this->belongsTo(Show::class);
    }
}
