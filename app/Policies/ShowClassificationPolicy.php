<?php

namespace App\Policies;

use App\ShowClassification;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShowClassificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param ShowClassification $showClassification
     * @return mixed
     */
    public function view(User $user, ShowClassification $showClassification)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param ShowClassification $showClassification
     * @return mixed
     */
    public function update(User $user, ShowClassification $showClassification)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param ShowClassification $showClassification
     * @return mixed
     */
    public function delete(User $user, ShowClassification $showClassification)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param ShowClassification $showClassification
     * @return mixed
     */
    public function restore(User $user, ShowClassification $showClassification)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param ShowClassification $showClassification
     * @return mixed
     */
    public function forceDelete(User $user, ShowClassification $showClassification)
    {
        //
    }
}
