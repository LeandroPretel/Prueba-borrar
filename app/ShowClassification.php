<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\ShowClassification
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $name
 * @property-read Collection|ShowTemplate[] $showTemplates
 * @property-read int|null $showTemplatesCount
 * @method static SavitarBuilder|ShowClassification newModelQuery()
 * @method static SavitarBuilder|ShowClassification newQuery()
 * @method static SavitarBuilder|ShowClassification query()
 * @method static Builder|ShowClassification whereCreatedAt($value)
 * @method static Builder|ShowClassification whereCreatedBy($value)
 * @method static Builder|ShowClassification whereDeletedAt($value)
 * @method static Builder|ShowClassification whereDeletedBy($value)
 * @method static Builder|ShowClassification whereId($value)
 * @method static Builder|ShowClassification whereName($value)
 * @method static Builder|ShowClassification whereUpdatedAt($value)
 * @method static Builder|ShowClassification whereUpdatedBy($value)
 * @mixin Eloquent
 */
class ShowClassification extends SavitarModel
{
    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'ShowClassification.name',
                'default' => true,
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'ShowClassification.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'ShowClassification.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'ShowClassification.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'ShowClassification.updatedBy',
            ],
        ],
    ];

    /**
     * A showClassification has many showTemplates
     *
     * @return HasMany
     */
    public function showTemplates(): HasMany
    {
        return $this->hasMany(ShowTemplate::class);
    }
}
