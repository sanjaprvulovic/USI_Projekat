<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StatusDegustacija;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusDegustacijaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the statusDegustacija can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the statusDegustacija can view the model.
     */
    public function view(User $user, StatusDegustacija $model): bool
    {
        return true;
    }

    /**
     * Determine whether the statusDegustacija can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the statusDegustacija can update the model.
     */
    public function update(User $user, StatusDegustacija $model): bool
    {
        return true;
    }

    /**
     * Determine whether the statusDegustacija can delete the model.
     */
    public function delete(User $user, StatusDegustacija $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     */
    public function deleteAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the statusDegustacija can restore the model.
     */
    public function restore(User $user, StatusDegustacija $model): bool
    {
        return false;
    }

    /**
     * Determine whether the statusDegustacija can permanently delete the model.
     */
    public function forceDelete(User $user, StatusDegustacija $model): bool
    {
        return false;
    }
}
