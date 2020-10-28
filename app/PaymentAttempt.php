<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Savitar\Models\SavitarBaseModel;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\Traits\UsesUUID;

/**
 * App\PaymentAttempt
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string $orderId
 * @property string $status
 * @property float $amount
 * @property string $paymentMethod
 * @property string|null $authorizationCode
 * @property int|null $redsysNumber
 * @property-read Order $order
 * @method static SavitarBuilder|PaymentAttempt newModelQuery()
 * @method static SavitarBuilder|PaymentAttempt newQuery()
 * @method static SavitarBuilder|PaymentAttempt query()
 * @method static Builder|PaymentAttempt whereAmount($value)
 * @method static Builder|PaymentAttempt whereAuthorizationCode($value)
 * @method static Builder|PaymentAttempt whereCreatedAt($value)
 * @method static Builder|PaymentAttempt whereCreatedBy($value)
 * @method static Builder|PaymentAttempt whereId($value)
 * @method static Builder|PaymentAttempt whereOrderId($value)
 * @method static Builder|PaymentAttempt wherePaymentMethod($value)
 * @method static Builder|PaymentAttempt whereRedsysNumber($value)
 * @method static Builder|PaymentAttempt whereStatus($value)
 * @method static Builder|PaymentAttempt whereUpdatedAt($value)
 * @method static Builder|PaymentAttempt whereUpdatedBy($value)
 * @mixin Eloquent
 */
class PaymentAttempt extends SavitarBaseModel
{
    use UsesUUID;

    protected $modelDefinition = [
        'visibleAttributes' => [
            'status' => [
                'name' => 'Estado',
                'type' => 'array',
                'sql' => 'PaymentAttempt.status',
                'default' => true,
                'possibleValues' => ['successful', 'failed', 'attempt'],
                'configuration' => [
                    'attempt' => ['html' => 'En proceso', 'translation' => 'En proceso', 'statusColor' => 'orange-status'],
                    'successful' => ['html' => 'Exitoso', 'translation' => 'Exitoso', 'statusColor' => 'green-status'],
                    'failed' => ['html' => 'Fallido', 'translation' => 'Fallido', 'statusColor' => 'red-status'],
                ],
            ],
            'amount' => [
                'name' => 'Importe',
                'type' => 'money',
                'sql' => 'PaymentAttempt.amount',
                'default' => true,
            ],
            'updatedAt' => [
                'name' => 'Fecha de pago',
                'type' => 'fullDate',
                'sql' => 'PaymentAttempt.updatedAt',
                'default' => true,
            ],
            'paymentMethod' => [
                'name' => 'Método de pago',
                'type' => 'array',
                'sql' => 'PaymentAttempt.paymentMethod',
                'default' => true,
                'possibleValues' => ['card', 'cash'],
                'configuration' => [
                    'card' => ['html' => 'Tarjeta', 'translation' => 'Tarjeta'],
                    'cash' => ['html' => 'Efectivo', 'translation' => 'Efectivo'],
                ],
            ],
            'authorizationCode' => [
                'name' => 'Código de autorización',
                'type' => 'string',
                'sql' => 'PaymentAttempt.authorizationCode',
            ],
            'redsysNumber' => [
                'name' => 'Número de operación',
                'type' => 'string',
                'sql' => 'PaymentAttempt.redsysNumber',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'PaymentAttempt.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'PaymentAttempt.createdBy',
            ],

            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'PaymentAttempt.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'order' => [
                'name' => 'Pedido',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Order::class,
            ],
        ],
    ];

    /**
     * Adds base attributes
     */
    protected function addSavitarBaseAttributes(): void
    {
        $attributes = Collection::make([
            'id' => [
                'name' => 'UUID',
                'validation' => 'uuid|required',
                'type' => 'uuid',
                'update' => false,
                'save' => false,
            ],
        ]);
        $attributesTemp = $this->parsedModel->union($attributes);
        $attributes->each(static function (&$item, $key) use ($attributesTemp, $attributes) {
            $item = Collection::make($attributesTemp[$key])->merge(Collection::make($item)->diffKeys(Collection::make($attributesTemp[$key])))->toArray();
            $attributes->put($key, $item);
        });
        $this->parsedModel = $this->parsedModel->merge($attributes);
    }

    /**
     * A paymentAttempt belongs to an order.
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }
}
