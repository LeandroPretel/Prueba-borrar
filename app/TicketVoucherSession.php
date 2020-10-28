<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\TicketVoucherSession
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $ticketVoucherId
 * @property string $sessionId
 * @property bool $required
 * @property-read Session $session
 * @property-read TicketVoucher $ticketVoucher
 * @method static SavitarBuilder|TicketVoucherSession newModelQuery()
 * @method static SavitarBuilder|TicketVoucherSession newQuery()
 * @method static SavitarBuilder|TicketVoucherSession query()
 * @method static Builder|TicketVoucherSession whereCreatedAt($value)
 * @method static Builder|TicketVoucherSession whereCreatedBy($value)
 * @method static Builder|TicketVoucherSession whereDeletedAt($value)
 * @method static Builder|TicketVoucherSession whereDeletedBy($value)
 * @method static Builder|TicketVoucherSession whereId($value)
 * @method static Builder|TicketVoucherSession whereSessionId($value)
 * @method static Builder|TicketVoucherSession whereTicketVoucherId($value)
 * @method static Builder|TicketVoucherSession whereUpdatedAt($value)
 * @method static Builder|TicketVoucherSession whereUpdatedBy($value)
 * @method static Builder|TicketVoucherSession whereRequired($value)
 * @mixin Eloquent
 */
class TicketVoucherSession extends SavitarPivotModel
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
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
}
