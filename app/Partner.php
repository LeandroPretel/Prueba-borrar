<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Partner
 *
 * @property string $id
 * @property string|null $observations
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $name
 * @property bool $isActive
 * @property string|null $liquidationUntilDate
 * @property int|null $oldId
 * @property float|null $commissionPercentage
 * @property float|null $commissionMinimum
 * @property float|null $commissionMaximum
 * @property-read Collection|Enterprise[] $enterprises
 * @property-read int|null $enterprisesCount
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read Collection|Session[] $sessions
 * @property-read int|null $sessionsCount
 * @method static Builder|Partner whereCreatedAt($value)
 * @method static Builder|Partner whereCreatedBy($value)
 * @method static Builder|Partner whereDeletedAt($value)
 * @method static Builder|Partner whereDeletedBy($value)
 * @method static Builder|Partner whereId($value)
 * @method static Builder|Partner whereIsActive($value)
 * @method static Builder|Partner whereLiquidationUntilDate($value)
 * @method static Builder|Partner whereName($value)
 * @method static Builder|Partner whereOldId($value)
 * @method static Builder|Partner whereUpdatedAt($value)
 * @method static Builder|Partner whereUpdatedBy($value)
 * @method static SavitarBuilder|Partner newModelQuery()
 * @method static SavitarBuilder|Partner newQuery()
 * @method static SavitarBuilder|Partner query()
 * @method static Builder|Partner whereObservations($value)
 * @method static Builder|Partner whereCommissionMaximum($value)
 * @method static Builder|Partner whereCommissionMinimum($value)
 * @method static Builder|Partner whereCommissionPercentage($value)
 * @mixin Eloquent
 */
class Partner extends SavitarModel
{
    use HasFiles;

    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Partner.name',
                'default' => true,
            ],
            'isActive' => [
                'name' => 'Estado',
                'type' => 'boolean',
                'sql' => 'Partner.isActive',
                'possibleValues' => [false, true],
                'configuration' => [
                    'false' => ['html' => 'Inactivo', 'translation' => 'Inactivo', 'statusColor' => 'red-status'],
                    'true' => ['html' => 'Activo', 'translation' => 'Activo', 'statusColor' => 'green-status'],
                ],
                'default' => true,
            ],
            'liquidationUntilDate' => [
                'name' => 'Fecha liquidación hasta',
                'type' => 'fullDate',
                'sql' => 'Partner.liquidationUntilDate',
            ],
            'commissionPercentage' => [
                'name' => 'Comisión (%)',
                'type' => 'decimal',
                'sql' => 'Partner.commissionPercentage',
                'default' => true,
            ],
            'commissionMinimum' => [
                'name' => 'Comisión mínima',
                'type' => 'money',
                'sql' => 'Partner.commissionMinimum',
                'default' => true,
            ],
            'commissionMaximum' => [
                'name' => 'Comisión máxima',
                'type' => 'money',
                'sql' => 'Partner.commissionMaximum',
                'default' => true,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Partner.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Partner.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Partner.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Partner.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Partner.updatedBy',
            ],
        ],
    ];

    /**
     * Get all of the enterprises for the enterprise.
     */
    public function enterprises(): MorphToMany
    {
        return $this->morphToMany(Enterprise::class, 'enterprisable', 'Enterprisable');
    }

    /**
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'PartnerSession')
            ->using(PartnerSession::class)
            ->withPivot([
                'createdAt',
                'updatedAt',
            ]);
    }
}
