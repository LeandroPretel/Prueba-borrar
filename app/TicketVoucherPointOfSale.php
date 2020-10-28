<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\TicketVoucherPointOfSale
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $ticketVoucherId
 * @property string $pointOfSaleId
 * @property-read PointOfSale $pointOfSale
 * @property-read TicketVoucher $ticketVoucher
 * @method static SavitarBuilder|TicketSeasonPointOfSale newModelQuery()
 * @method static SavitarBuilder|TicketSeasonPointOfSale newQuery()
 * @method static SavitarBuilder|TicketSeasonPointOfSale query()
 * @method static Builder|TicketSeasonPointOfSale whereCreatedAt($value)
 * @method static Builder|TicketSeasonPointOfSale whereCreatedBy($value)
 * @method static Builder|TicketSeasonPointOfSale whereId($value)
 * @method static Builder|TicketSeasonPointOfSale wherePointOfSaleId($value)
 * @method static Builder|TicketSeasonPointOfSale whereTicketVoucherId($value)
 * @method static Builder|TicketSeasonPointOfSale whereUpdatedAt($value)
 * @method static Builder|TicketSeasonPointOfSale whereUpdatedBy($value)
 * @mixin Eloquent
 */
class TicketVoucherPointOfSale extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function ticketVoucher(): BelongsTo
    {
        return $this->belongsTo(TicketVoucher::class);
    }

    /**
     * @return BelongsTo
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }
}
