<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Degustacija;
use Illuminate\Auth\Access\HandlesAuthorization;

class DegustacijaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the degustacija can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the degustacija can view the model.
     */
    public function view(User $user, Degustacija $model): bool
    {
        return true;
    }

    /**
     * Determine whether the degustacija can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the degustacija can update the model.
     */
    public function update(User $user, Degustacija $model): bool
    {
        return true;
    }

    /**
     * Determine whether the degustacija can delete the model.
     */
    public function delete(User $user, Degustacija $model): bool
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
     * Determine whether the degustacija can restore the model.
     */
    public function restore(User $user, Degustacija $model): bool
    {
        return false;
    }

    /**
     * Determine whether the degustacija can permanently delete the model.
     */
    public function forceDelete(User $user, Degustacija $model): bool
    {
        return false;
    }
}
