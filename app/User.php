<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Savitar\Auth\SavitarAccount;
use Savitar\Auth\SavitarRole;
use Savitar\Auth\SavitarSocialEntity;
use Savitar\Auth\SavitarUser;
use Savitar\Auth\SavitarZone;
use Savitar\Files\SavitarFile;
use Savitar\Models\SavitarBuilder;

/**
 * App\User
 *
 * @property string $id
 * @property string|null $accountId
 * @property string $roleId
 * @property string|null $countryId
 * @property string|null $provinceId
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $nif
 * @property string|null $birthDate
 * @property string|null $phone
 * @property string $email
 * @property string|null $socialNickname
 * @property string|null $socialAvatar
 * @property string|null $password
 * @property bool $isFirstLogin
 * @property bool $isActive
 * @property bool $emailConfirmed
 * @property bool $canReceiveNotifications
 * @property bool $canReceiveEmails
 * @property string|null $observations
 * @property string|null $createdBy
 * @property string|null $updatedBy
 * @property string|null $deletedBy
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property-read SavitarAccount|null $account
 * @property-read SavitarZone|null $country
 * @property-read User|null $createdByUser
 * @property-read User|null $deletedByUser
 * @property-read Collection|SavitarFile[] $files
 * @property-read int|null $filesCount
 * @property-read mixed|string $profileImageUrl
 * @property-read SavitarZone|null $province
 * @property-read SavitarRole $role
 * @property-read Collection|SavitarSocialEntity[] $socialEntities
 * @property-read int|null $socialEntitiesCount
 * @property-read User|null $updatedByUser
 * @method static SavitarBuilder|User newModelQuery()
 * @method static SavitarBuilder|User newQuery()
 * @method static SavitarBuilder|User query()
 * @method static Builder|User whereAccountId($value)
 * @method static Builder|User whereBirthDate($value)
 * @method static Builder|User whereCanReceiveEmails($value)
 * @method static Builder|User whereCanReceiveNotifications($value)
 * @method static Builder|User whereCountryId($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereCreatedBy($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereDeletedBy($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailConfirmed($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsActive($value)
 * @method static Builder|User whereIsFirstLogin($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereNif($value)
 * @method static Builder|User whereObservations($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereProvinceId($value)
 * @method static Builder|User whereRoleId($value)
 * @method static Builder|User whereSocialAvatar($value)
 * @method static Builder|User whereSocialNickname($value)
 * @method static Builder|User whereSurname($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUpdatedBy($value)
 * @mixin Eloquent
 * @property int|null $oldId
 * @method static Builder|User whereOldId($value)
 */
class User extends SavitarUser
{
    /**
     * An user has many files
     *
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(SavitarFile::class, 'fileableId');
    }
}
