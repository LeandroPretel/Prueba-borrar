<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\PartnerSession
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $partnerId
 * @property string $sessionId
 * @property float|null $commissionPercentage
 * @property float|null $commissionMinimum
 * @property float|null $commissionMaximum
 * @property-read Partner $partner
 * @property-read Session $session
 * @method static SavitarBuilder|PartnerSession newModelQuery()
 * @method static SavitarBuilder|PartnerSession newQuery()
 * @method static SavitarBuilder|PartnerSession query()
 * @method static Builder|PartnerSession whereCreatedAt($value)
 * @method static Builder|PartnerSession whereCreatedBy($value)
 * @method static Builder|PartnerSession whereId($value)
 * @method static Builder|PartnerSession wherePartnerId($value)
 * @method static Builder|PartnerSession whereSessionId($value)
 * @method static Builder|PartnerSession whereUpdatedAt($value)
 * @method static Builder|PartnerSession whereUpdatedBy($value)
 * @method static Builder|PartnerSession whereCommissionMaximum($value)
 * @method static Builder|PartnerSession whereCommissionMinimum($value)
 * @method static Builder|PartnerSession whereCommissionPercentage($value)
 * @mixin Eloquent
 */
class PartnerSession extends SavitarPivotModel
{
    protected $fillable = [
        'commissionPercentage',
        'commissionMinimum',
        'commissionMaximum',
    ];

    /**
     * @return BelongsTo
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class)->withTrashed();
    }
}
