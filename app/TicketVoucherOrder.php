<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\TicketVoucherOrder
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $ticketVoucherId
 * @property string $orderId
 * @property-read Order $order
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @property-read TicketVoucher $ticketVoucher
 * @method static SavitarBuilder|TicketVoucherOrder newModelQuery()
 * @method static SavitarBuilder|TicketVoucherOrder newQuery()
 * @method static SavitarBuilder|TicketVoucherOrder query()
 * @method static Builder|TicketVoucherOrder whereCreatedAt($value)
 * @method static Builder|TicketVoucherOrder whereCreatedBy($value)
 * @method static Builder|TicketVoucherOrder whereDeletedAt($value)
 * @method static Builder|TicketVoucherOrder whereDeletedBy($value)
 * @method static Builder|TicketVoucherOrder whereId($value)
 * @method static Builder|TicketVoucherOrder whereOrderId($value)
 * @method static Builder|TicketVoucherOrder whereTicketVoucherId($value)
 * @method static Builder|TicketVoucherOrder whereUpdatedAt($value)
 * @method static Builder|TicketVoucherOrder whereUpdatedBy($value)
 * @mixin Eloquent
 */
class TicketVoucherOrder extends SavitarModel
{
    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function ticketVoucher(): BelongsTo
    {
        return $this->belongsTo(TicketVoucher::class)->withTrashed();
    }

    /**
     * A ticketVoucherOrder belongs to many sessions
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'TicketVoucherOrderSession')
            ->using(TicketVoucherOrderSession::class)
            ->withTimestamps();
    }
}
