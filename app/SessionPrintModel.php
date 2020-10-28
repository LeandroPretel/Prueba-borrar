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
 * App\SessionPrintModel
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $sessionId
 * @property string $font
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read Session $session
 * @property-read mixed|string $mainImageUrl
 * @method static SavitarBuilder|SessionPrintModel newModelQuery()
 * @method static SavitarBuilder|SessionPrintModel newQuery()
 * @method static SavitarBuilder|SessionPrintModel query()
 * @method static Builder|SessionPrintModel whereCreatedAt($value)
 * @method static Builder|SessionPrintModel whereCreatedBy($value)
 * @method static Builder|SessionPrintModel whereFont($value)
 * @method static Builder|SessionPrintModel whereId($value)
 * @method static Builder|SessionPrintModel whereSessionId($value)
 * @method static Builder|SessionPrintModel whereUpdatedAt($value)
 * @method static Builder|SessionPrintModel whereUpdatedBy($value)
 * @mixin Eloquent
 */
class SessionPrintModel extends SavitarBaseModel
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
     * A sessionPrintModel belongsTo a session.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
}
