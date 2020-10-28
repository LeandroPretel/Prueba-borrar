<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\ShowCategory
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $name
 * @property string|null $observations
 * @property float $sgaeFee
 * @property float $vat
 * @property int|null $oldId
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read Collection|ShowTemplate[] $showTemplates
 * @property-read int|null $showTemplatesCount
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|ShowCategory newModelQuery()
 * @method static SavitarBuilder|ShowCategory newQuery()
 * @method static SavitarBuilder|ShowCategory query()
 * @method static Builder|ShowCategory whereCreatedAt($value)
 * @method static Builder|ShowCategory whereCreatedBy($value)
 * @method static Builder|ShowCategory whereDeletedAt($value)
 * @method static Builder|ShowCategory whereDeletedBy($value)
 * @method static Builder|ShowCategory whereId($value)
 * @method static Builder|ShowCategory whereName($value)
 * @method static Builder|ShowCategory whereSgaeFee($value)
 * @method static Builder|ShowCategory whereUpdatedAt($value)
 * @method static Builder|ShowCategory whereUpdatedBy($value)
 * @method static Builder|ShowCategory whereVat($value)
 * @method static Builder|ShowCategory whereObservations($value)
 * @method static Builder|ShowCategory whereOldId($value)
 * @mixin Eloquent
 */
class ShowCategory extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'ShowCategory.name',
                'default' => true,
            ],
            'sgaeFee' => [
                'name' => 'Tarifa SGAE',
                'type' => 'array',
                'sql' => 'ShowCategory.sgaeFee',
                'possibleValues' => [0.00, 1.5, 3.5, 5.45, 9.00, 10.00],
                'configuration' => [
                    '0.00' => ['html' => 'EXENTO (0%)', 'translation' => 'EXENTO (0%)'],
                    '1.50' => ['html' => 'TEATRO (1.5%)', 'translation' => 'TEATRO (1.5%)'],
                    '3.50' => ['html' => 'TEATRO (3.5%)', 'translation' => 'TEATRO (3.5%)'],
                    '5.45' => ['html' => 'VARIEDADES (5,45%)', 'translation' => 'VARIEDADES (5,45%)'],
                    '9.00' => ['html' => 'TEATRO (9%)', 'translation' => 'TEATRO (9%)'],
                    '10.00' => ['html' => 'CONCIERTOS-TEATRO (10%)', 'translation' => 'CONCIERTOS-TEATRO (10%)'],
                ],
                'default' => true,
            ],
            'vat' => [
                'name' => 'Tipo de IVA',
                'type' => 'array',
                'sql' => 'ShowCategory.vat',
                'possibleValues' => [0.00, 4.00, 10.00, 21.00],
                'configuration' => [
                    '0.00' => ['html' => 'EXENTO (0%)', 'translation' => 'EXENTO (0%)'],
                    '4.00' => ['html' => 'SUPERREDUCIDO (4%)', 'translation' => 'SUPERREDUCIDO (4%)'],
                    '10.00' => ['html' => 'REDUCIDO (10%)', 'translation' => 'REDUCIDO (10%)'],
                    '21.00' => ['html' => 'GENERAL (21%)', 'translation' => 'GENERAL (21%)'],
                ],
                'default' => true,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'ShowCategory.observations',
            ],
        ],
        'shadowAttributes' => [
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'ShowCategory.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'ShowCategory.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'ShowCategory.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'ShowCategory.updatedBy',
            ],
        ]
    ];
    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'createdBy',
        'createdAt',
        'updatedBy',
        'updatedAt',
        'deletedBy',
        'deletedAt',
    ];

    /**
     * @return BelongsToMany
     */
    public function showTemplates(): BelongsToMany
    {
        return $this->belongsToMany(ShowTemplate::class)->using(ShowCategoryShowTemplate::class)->withTimestamps();
    }
}
