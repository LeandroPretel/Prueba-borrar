<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\InvoiceNumber
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property int $number
 * @property int $yearNumber
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|InvoiceNumber newModelQuery()
 * @method static SavitarBuilder|InvoiceNumber newQuery()
 * @method static SavitarBuilder|InvoiceNumber query()
 * @method static Builder|InvoiceNumber whereCreatedAt($value)
 * @method static Builder|InvoiceNumber whereCreatedBy($value)
 * @method static Builder|InvoiceNumber whereDeletedAt($value)
 * @method static Builder|InvoiceNumber whereDeletedBy($value)
 * @method static Builder|InvoiceNumber whereId($value)
 * @method static Builder|InvoiceNumber whereNumber($value)
 * @method static Builder|InvoiceNumber whereUpdatedAt($value)
 * @method static Builder|InvoiceNumber whereUpdatedBy($value)
 * @method static Builder|InvoiceNumber whereYearNumber($value)
 * @mixin Eloquent
 */
class InvoiceNumber extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'number' => [
                'name' => 'Número',
                'type' => 'int',
                'sql' => 'InvoiceNumber.number',
                'default' => true,
            ],
            /*
            'type' => [
                'name' => 'Tipo',
                'type' => 'array',
                'sql' => 'Recharge.type',
                'default' => true,
                'possibleValues' => ['recharge', 'invoice'],
                'configuration' => [
                    'recharge' => ['html' => 'Recarga', 'translation' => 'Recarga'],
                    'invoice' => ['html' => 'Factura', 'translation' => 'Factura'],
                ],
            ],
            */
            'yearNumber' => [
                'name' => 'Año',
                'type' => 'int',
                'sql' => 'InvoiceNumber.yearNumber',
                'default' => true,
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'InvoiceNumber.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'InvoiceNumber.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'InvoiceNumber.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'InvoiceNumber.updatedBy',
            ],
        ],
        'shadowAttributes' => [
        ],
    ];

    protected $hidden = [
        'createdAt',
        'createdBy',
        'updatedAt',
        'updatedBy',
        'deletedAt',
        'deletedBy',
    ];
}
