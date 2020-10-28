<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\SessionAreaFare
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $sessionAreaId
 * @property string $fareId
 * @property bool $isActive
 * @property float $earlyPrice
 * @property float $earlyDistributionPrice
 * @property float $earlyTotalPrice
 * @property float $ticketOfficePrice
 * @property float $ticketOfficeDistributionPrice
 * @property float $ticketOfficeTotalPrice
 * @property-read Fare $fare
 * @property-read SessionArea $sessionArea
 * @property-read Collection|Ticket[] $tickets
 * @property-read int|null $ticketsCount
 * @method static SavitarBuilder|SessionAreaFare newModelQuery()
 * @method static SavitarBuilder|SessionAreaFare newQuery()
 * @method static SavitarBuilder|SessionAreaFare query()
 * @method static Builder|SessionAreaFare whereCreatedAt($value)
 * @method static Builder|SessionAreaFare whereCreatedBy($value)
 * @method static Builder|SessionAreaFare whereEarlyDistributionPrice($value)
 * @method static Builder|SessionAreaFare whereEarlyPrice($value)
 * @method static Builder|SessionAreaFare whereEarlyTotalPrice($value)
 * @method static Builder|SessionAreaFare whereFareId($value)
 * @method static Builder|SessionAreaFare whereId($value)
 * @method static Builder|SessionAreaFare whereIsActive($value)
 * @method static Builder|SessionAreaFare whereSessionAreaId($value)
 * @method static Builder|SessionAreaFare whereTicketOfficeDistributionPrice($value)
 * @method static Builder|SessionAreaFare whereTicketOfficePrice($value)
 * @method static Builder|SessionAreaFare whereTicketOfficeTotalPrice($value)
 * @method static Builder|SessionAreaFare whereUpdatedAt($value)
 * @method static Builder|SessionAreaFare whereUpdatedBy($value)
 * @mixin Eloquent
 */
class SessionAreaFare extends SavitarPivotModel
{
    protected $fillable = [
        'isActive',
        'earlyPrice',
        'earlyDistributionPrice',
        'earlyTotalPrice',
        'ticketOfficePrice',
        'ticketOfficeDistributionPrice',
        'ticketOfficeTotalPrice',
    ];

    /**
     * @return BelongsTo
     */
    public function sessionArea(): BelongsTo
    {
        return $this->belongsTo(SessionArea::class);
    }

    /**
     * @return BelongsTo
     */
    public function fare(): BelongsTo
    {
        return $this->belongsTo(Fare::class);
    }

    /**
     * An sessionAreaFare has many tickets
     *
     * @return HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
