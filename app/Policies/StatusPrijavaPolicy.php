<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StatusPrijava;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPrijavaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the statusPrijava can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the statusPrijava can view the model.
     */
    public function view(User $user, StatusPrijava $model): bool
    {
        return true;
    }

    /**
     * Determine whether the statusPrijava can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the statusPrijava can update the model.
     */
    public function update(User $user, StatusPrijava $model): bool
    {
        return true;
    }

    /**
     * Determine whether the statusPrijava can delete the model.
     */
    public function delete(User $user, StatusPrijava $model): bool
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
     * Determine whether the statusPrijava can restore the model.
     */
    public function restore(User $user, StatusPrijava $model): bool
    {
        return false;
    }

    /**
     * Determine whether the statusPrijava can permanently delete the model.
     */
    public function forceDelete(User $user, StatusPrijava $model): bool
    {
        return false;
    }
}
