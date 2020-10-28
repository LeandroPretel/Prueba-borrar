<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarPivotModel;

/**
 * App\AccountPointOfSale
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $accountId
 * @property string $pointOfSaleId
 * @property-read Account $account
 * @property-read User|null $createdByUser
 * @property-read PointOfSale $pointOfSale
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|AccountPointOfSale newModelQuery()
 * @method static SavitarBuilder|AccountPointOfSale newQuery()
 * @method static SavitarBuilder|AccountPointOfSale query()
 * @method static Builder|AccountPointOfSale whereAccountId($value)
 * @method static Builder|AccountPointOfSale whereCreatedAt($value)
 * @method static Builder|AccountPointOfSale whereCreatedBy($value)
 * @method static Builder|AccountPointOfSale whereId($value)
 * @method static Builder|AccountPointOfSale wherePointOfSaleId($value)
 * @method static Builder|AccountPointOfSale whereUpdatedAt($value)
 * @method static Builder|AccountPointOfSale whereUpdatedBy($value)
 * @mixin Eloquent
 */
class AccountPointOfSale extends SavitarPivotModel
{
    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return BelongsTo
     */
    public function pointOfSale(): BelongsTo
    {
        return $this->belongsTo(PointOfSale::class);
    }
}
