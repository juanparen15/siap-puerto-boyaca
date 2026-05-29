<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FinanciamientoRecurso;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinanciamientoRecursoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FinanciamientoRecurso');
    }

    public function view(AuthUser $authUser, FinanciamientoRecurso $financiamientoRecurso): bool
    {
        return $authUser->can('View:FinanciamientoRecurso');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FinanciamientoRecurso');
    }

    public function update(AuthUser $authUser, FinanciamientoRecurso $financiamientoRecurso): bool
    {
        return $authUser->can('Update:FinanciamientoRecurso');
    }

    public function delete(AuthUser $authUser, FinanciamientoRecurso $financiamientoRecurso): bool
    {
        return $authUser->can('Delete:FinanciamientoRecurso');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:FinanciamientoRecurso');
    }

    public function restore(AuthUser $authUser, FinanciamientoRecurso $financiamientoRecurso): bool
    {
        return $authUser->can('Restore:FinanciamientoRecurso');
    }

    public function forceDelete(AuthUser $authUser, FinanciamientoRecurso $financiamientoRecurso): bool
    {
        return $authUser->can('ForceDelete:FinanciamientoRecurso');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FinanciamientoRecurso');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FinanciamientoRecurso');
    }

    public function replicate(AuthUser $authUser, FinanciamientoRecurso $financiamientoRecurso): bool
    {
        return $authUser->can('Replicate:FinanciamientoRecurso');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FinanciamientoRecurso');
    }

}