<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\ShowCategoryShowTemplate
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $showCategoryId
 * @property string $showTemplateId
 * @property-read User|null $createdByUser
 * @property-read ShowCategory $showCategory
 * @property-read ShowTemplate $showTemplate
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|ShowCategoryShowTemplate newModelQuery()
 * @method static SavitarBuilder|ShowCategoryShowTemplate newQuery()
 * @method static SavitarBuilder|ShowCategoryShowTemplate query()
 * @method static Builder|ShowCategoryShowTemplate whereCreatedAt($value)
 * @method static Builder|ShowCategoryShowTemplate whereCreatedBy($value)
 * @method static Builder|ShowCategoryShowTemplate whereId($value)
 * @method static Builder|ShowCategoryShowTemplate whereShowCategoryId($value)
 * @method static Builder|ShowCategoryShowTemplate whereShowTemplateId($value)
 * @method static Builder|ShowCategoryShowTemplate whereUpdatedAt($value)
 * @method static Builder|ShowCategoryShowTemplate whereUpdatedBy($value)
 * @mixin Eloquent
 */
class ShowCategoryShowTemplate extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function showCategory(): BelongsTo
    {
        return $this->belongsTo(ShowCategory::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function showTemplate(): BelongsTo
    {
        return $this->belongsTo(ShowTemplate::class)->withTrashed();
    }
}
