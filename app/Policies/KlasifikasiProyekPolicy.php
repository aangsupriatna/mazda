<?php

namespace App\Policies;

use App\Models\User;
use App\Models\KlasifikasiProyek;
use Illuminate\Auth\Access\HandlesAuthorization;

class KlasifikasiProyekPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KlasifikasiProyek $klasifikasiProyek): bool
    {
        return $user->can('view_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KlasifikasiProyek $klasifikasiProyek): bool
    {
        return $user->can('update_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KlasifikasiProyek $klasifikasiProyek): bool
    {
        return $user->can('delete_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, KlasifikasiProyek $klasifikasiProyek): bool
    {
        return $user->can('force_delete_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, KlasifikasiProyek $klasifikasiProyek): bool
    {
        return $user->can('restore_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, KlasifikasiProyek $klasifikasiProyek): bool
    {
        return $user->can('replicate_klasifikasi::proyek');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_klasifikasi::proyek');
    }
}
