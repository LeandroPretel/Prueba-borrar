<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Consultation
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $fullName
 * @property string $email
 * @property string $phone
 * @property string $consultationReason
 * @property string $consultationText
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|Consultation newModelQuery()
 * @method static SavitarBuilder|Consultation newQuery()
 * @method static SavitarBuilder|Consultation query()
 * @method static Builder|Consultation whereConsultationReason($value)
 * @method static Builder|Consultation whereConsultationText($value)
 * @method static Builder|Consultation whereCreatedAt($value)
 * @method static Builder|Consultation whereCreatedBy($value)
 * @method static Builder|Consultation whereDeletedAt($value)
 * @method static Builder|Consultation whereDeletedBy($value)
 * @method static Builder|Consultation whereEmail($value)
 * @method static Builder|Consultation whereFullName($value)
 * @method static Builder|Consultation whereId($value)
 * @method static Builder|Consultation wherePhone($value)
 * @method static Builder|Consultation whereUpdatedAt($value)
 * @method static Builder|Consultation whereUpdatedBy($value)
 * @mixin Eloquent
 */
class Consultation extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'fullName' => [
                'name' => 'Nombre y apellidos',
                'type' => 'string',
                'sql' => 'Consultation.fullName',
                'default' => true,
            ],
            'email' => [
                'name' => 'Email',
                'type' => 'string',
                'sql' => 'Consultation.email',
                'default' => true,
            ],
            'phone' => [
                'name' => 'Teléfono',
                'type' => 'date',
                'sql' => 'Consultation.phone',
                'default' => true,
            ],
            'consultationReason' => [
                'name' => 'Motivo de la consulta',
                'type' => 'string',
                'sql' => 'Consultation.consultationReason',
                'default' => true,
            ],
            'consultationText' => [
                'name' => 'Texto de la consulta',
                'type' => 'string',
                'sql' => 'Consultation.consultationText',
                'default' => true,
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Consultation.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Consultation.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Consultation.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Consultation.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'account' => [
                'name' => 'Cuenta',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Consultation::class,
            ],
        ],
    ];

    protected $hidden = [
//        'createdAt',
//        'createdBy',
        'updatedAt',
        'updatedBy',
        'deletedAt',
        'deletedBy',
    ];
}
