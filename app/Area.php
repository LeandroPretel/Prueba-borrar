<?php

namespace App;

use App\Http\Controllers\SectorController;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Files\SavitarFile;
use Savitar\Files\Traits\HasFiles;
use Savitar\Models\SavitarBuilder;
use Savitar\Models\SavitarModel;

/**
 * App\Area
 *
 * @property string $id
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property string $spaceId
 * @property string $name
 * @property string $webName
 * @property string $ticketName
 * @property string $color
 * @property int $totalSeats
 * @property int $order
 * @property string|null $observations
 * @property int|null $oldId
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read Collection|Sector[] $sectors
 * @property-read int|null $sectorsCount
 * @property-read Space $space
 * @method static SavitarBuilder|Area newModelQuery()
 * @method static SavitarBuilder|Area newQuery()
 * @method static SavitarBuilder|Area query()
 * @method static Builder|Area whereColor($value)
 * @method static Builder|Area whereCreatedAt($value)
 * @method static Builder|Area whereCreatedBy($value)
 * @method static Builder|Area whereDeletedAt($value)
 * @method static Builder|Area whereDeletedBy($value)
 * @method static Builder|Area whereId($value)
 * @method static Builder|Area whereName($value)
 * @method static Builder|Area whereObservations($value)
 * @method static Builder|Area whereOldId($value)
 * @method static Builder|Area whereSpaceId($value)
 * @method static Builder|Area whereTicketName($value)
 * @method static Builder|Area whereTotalSeats($value)
 * @method static Builder|Area whereUpdatedAt($value)
 * @method static Builder|Area whereUpdatedBy($value)
 * @method static Builder|Area whereWebName($value)
 * @method static Builder|Area whereOrder($value)
 * @mixin Eloquent
 */
class Area extends SavitarModel
{
    use HasFiles;

    protected $modelDefinition = [
        'visibleAttributes' => [
            'name' => [
                'name' => 'Nombre',
                'type' => 'string',
                'sql' => 'Area.name',
                'default' => true,
            ],
            'webName' => [
                'name' => 'Nombre en la web',
                'type' => 'string',
                'sql' => 'Area.webName',
                'default' => true,
            ],
            'ticketName' => [
                'name' => 'Nombre en la entrada',
                'type' => 'string',
                'sql' => 'Area.ticketName',
                'default' => true,
            ],
            'order' => [
                'name' => 'Orden',
                'type' => 'int',
                'sql' => 'Area.order',
                'default' => true,
            ],
            'totalSeats' => [
                'name' => 'Aforo',
                'type' => 'number',
                'sql' => 'Area.totalSeats',
                'default' => true,
            ],
            'observations' => [
                'name' => 'Observaciones',
                'type' => 'string',
                'sql' => 'Area.observations',
            ],
            'createdAt' => [
                'name' => 'Fecha de creación',
                'type' => 'fullDate',
                'sql' => 'Area.createdAt',
            ],
            'createdBy' => [
                'name' => 'Creador',
                'type' => 'string',
                'sql' => 'Area.createdBy',
            ],
            'updatedAt' => [
                'name' => 'Fecha de actualización',
                'type' => 'fullDate',
                'sql' => 'Area.updatedAt',
            ],
            'updatedBy' => [
                'name' => 'Actualizado por',
                'type' => 'string',
                'sql' => 'Area.updatedBy',
            ],
        ],
        'shadowAttributes' => [
            'space' => [
                'name' => 'Espacio',
                'type' => 'relation',
                'relationType' => 'belongsTo',
                'relatedModelClass' => Space::class,
            ],
            'sectors' => [
                'name' => 'Sectores',
                'type' => 'relation',
                'relationType' => 'hasMany',
                'relatedModelClass' => Sector::class,
                'relatedModelControllerClass' => SectorController::class,
            ],
            'color' => [
                'name' => 'Color',
                'type' => 'string',
                'sql' => 'Area.color',
            ],
        ],
    ];

//    protected $appends = ['mainImageUrl'];
//
//    protected $filesInputKeys = ['files', 'mainImage'];
//
//    /**
//     * @return mixed|string
//     */
//    public function getMainImageUrlAttribute()
//    {
//        $photo = $this->files()->where('category', 'mainImage')->first();
//        return $photo ? $photo->url : 'assets/aforo.svg';
//    }

    /**
     * An area belongs to a space
     *
     * @return BelongsTo
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    /**
     * An area has many sectors
     *
     * @return HasMany
     */
    public function sectors(): HasMany
    {
        return $this->hasMany(Sector::class)
            ->orderBy('order')
            ->orderBy('createdAt', 'asc');
    }
}
