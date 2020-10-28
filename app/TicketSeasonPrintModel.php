<?php

namespace App;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarBaseModel;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\Traits\UsesUUID;

/**
 * App\TicketSeasonPrintModel
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $ticketSeasonId
 * @property string $font
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read TicketSeason $ticketSeason
 * @property-read mixed|string $mainImageUrl
 * @method static SavitarBuilder|TicketSeasonPrintModel newModelQuery()
 * @method static SavitarBuilder|TicketSeasonPrintModel newQuery()
 * @method static SavitarBuilder|TicketSeasonPrintModel query()
 * @method static Builder|TicketSeasonPrintModel whereCreatedAt($value)
 * @method static Builder|TicketSeasonPrintModel whereCreatedBy($value)
 * @method static Builder|TicketSeasonPrintModel whereFont($value)
 * @method static Builder|TicketSeasonPrintModel whereId($value)
 * @method static Builder|TicketSeasonPrintModel whereTicketSeasonId($value)
 * @method static Builder|TicketSeasonPrintModel whereUpdatedAt($value)
 * @method static Builder|TicketSeasonPrintModel whereUpdatedBy($value)
 * @mixin Eloquent
 */
class TicketSeasonPrintModel extends SavitarBaseModel
{
    use UsesUUID;
    use HasFiles;

    /**
     * @var array
     */
    protected $appends = ['mainImageUrl'];

    protected $filesInputKeys = ['files', 'mainImage'];

    /**
     * @return mixed|string
     * @throws Exception
     */
    public function getMainImageUrlAttribute()
    {
        $photo = $this->files()->where('category', 'mainImage')->first();
        return $photo ? $photo->url : null;
    }

    /**
     * A ticketSeasonPrintModel belongsTo a ticketSeason.
     *
     * @return BelongsTo
     */
    public function ticketSeason(): BelongsTo
    {
        return $this->belongsTo(TicketSeason::class);
    }
}
