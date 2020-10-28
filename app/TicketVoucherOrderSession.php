<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\TicketVoucherOrderSession
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $ticketVoucherOrderId
 * @property string $sessionId
 * @property-read Session $session
 * @property-read TicketVoucherOrder $ticketVoucherOrder
 * @method static SavitarBuilder|TicketVoucherOrderSession newModelQuery()
 * @method static SavitarBuilder|TicketVoucherOrderSession newQuery()
 * @method static SavitarBuilder|TicketVoucherOrderSession query()
 * @method static Builder|TicketVoucherOrderSession whereCreatedAt($value)
 * @method static Builder|TicketVoucherOrderSession whereCreatedBy($value)
 * @method static Builder|TicketVoucherOrderSession whereDeletedAt($value)
 * @method static Builder|TicketVoucherOrderSession whereDeletedBy($value)
 * @method static Builder|TicketVoucherOrderSession whereId($value)
 * @method static Builder|TicketVoucherOrderSession whereSessionId($value)
 * @method static Builder|TicketVoucherOrderSession whereTicketVoucherOrderId($value)
 * @method static Builder|TicketVoucherOrderSession whereUpdatedAt($value)
 * @method static Builder|TicketVoucherOrderSession whereUpdatedBy($value)
 * @mixin Eloquent
 */
class TicketVoucherOrderSession extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function ticketVoucherOrder(): BelongsTo
    {
        return $this->belongsTo(TicketVoucherOrder::class)->withTrashed();
    }

    /**
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class)->withTrashed();
    }
}
