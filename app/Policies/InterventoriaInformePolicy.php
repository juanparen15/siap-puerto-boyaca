<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InterventoriaInforme;
use Illuminate\Auth\Access\HandlesAuthorization;

class InterventoriaInformePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InterventoriaInforme');
    }

    public function view(AuthUser $authUser, InterventoriaInforme $interventoriaInforme): bool
    {
        return $authUser->can('View:InterventoriaInforme');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InterventoriaInforme');
    }

    public function update(AuthUser $authUser, InterventoriaInforme $interventoriaInforme): bool
    {
        return $authUser->can('Update:InterventoriaInforme');
    }

    public function delete(AuthUser $authUser, InterventoriaInforme $interventoriaInforme): bool
    {
        return $authUser->can('Delete:InterventoriaInforme');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:InterventoriaInforme');
    }

    public function restore(AuthUser $authUser, InterventoriaInforme $interventoriaInforme): bool
    {
        return $authUser->can('Restore:InterventoriaInforme');
    }

    public function forceDelete(AuthUser $authUser, InterventoriaInforme $interventoriaInforme): bool
    {
        return $authUser->can('ForceDelete:InterventoriaInforme');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InterventoriaInforme');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InterventoriaInforme');
    }

    public function replicate(AuthUser $authUser, InterventoriaInforme $interventoriaInforme): bool
    {
        return $authUser->can('Replicate:InterventoriaInforme');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InterventoriaInforme');
    }

}