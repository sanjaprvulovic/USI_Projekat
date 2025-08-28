<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DegustacijaPaket;
use Illuminate\Auth\Access\HandlesAuthorization;

class DegustacijaPaketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the degustacijaPaket can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the degustacijaPaket can view the model.
     */
    public function view(User $user, DegustacijaPaket $model): bool
    {
        return true;
    }

    /**
     * Determine whether the degustacijaPaket can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the degustacijaPaket can update the model.
     */
    public function update(User $user, DegustacijaPaket $model): bool
    {
        return true;
    }

    /**
     * Determine whether the degustacijaPaket can delete the model.
     */
    public function delete(User $user, DegustacijaPaket $model): bool
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
     * Determine whether the degustacijaPaket can restore the model.
     */
    public function restore(User $user, DegustacijaPaket $model): bool
    {
        return false;
    }

    /**
     * Determine whether the degustacijaPaket can permanently delete the model.
     */
    public function forceDelete(User $user, DegustacijaPaket $model): bool
    {
        return false;
    }
}
