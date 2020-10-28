<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\ArtistShowTemplate
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $artistId
 * @property string $showTemplateId
 * @property-read Artist $artist
 * @property-read User|null $createdByUser
 * @property-read ShowTemplate $showTemplate
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|ArtistShowTemplate newModelQuery()
 * @method static SavitarBuilder|ArtistShowTemplate newQuery()
 * @method static SavitarBuilder|ArtistShowTemplate query()
 * @method static Builder|ArtistShowTemplate whereArtistId($value)
 * @method static Builder|ArtistShowTemplate whereCreatedAt($value)
 * @method static Builder|ArtistShowTemplate whereCreatedBy($value)
 * @method static Builder|ArtistShowTemplate whereId($value)
 * @method static Builder|ArtistShowTemplate whereShowTemplateId($value)
 * @method static Builder|ArtistShowTemplate whereUpdatedAt($value)
 * @method static Builder|ArtistShowTemplate whereUpdatedBy($value)
 * @mixin Eloquent
 */
class ArtistShowTemplate extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    /**
     * @return BelongsTo
     */
    public function showTemplate(): BelongsTo
    {
        return $this->belongsTo(ShowTemplate::class);
    }
}
