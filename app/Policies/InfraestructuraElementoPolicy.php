<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InfraestructuraElemento;
use Illuminate\Auth\Access\HandlesAuthorization;

class InfraestructuraElementoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InfraestructuraElemento');
    }

    public function view(AuthUser $authUser, InfraestructuraElemento $infraestructuraElemento): bool
    {
        return $authUser->can('View:InfraestructuraElemento');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InfraestructuraElemento');
    }

    public function update(AuthUser $authUser, InfraestructuraElemento $infraestructuraElemento): bool
    {
        return $authUser->can('Update:InfraestructuraElemento');
    }

    public function delete(AuthUser $authUser, InfraestructuraElemento $infraestructuraElemento): bool
    {
        return $authUser->can('Delete:InfraestructuraElemento');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:InfraestructuraElemento');
    }

    public function restore(AuthUser $authUser, InfraestructuraElemento $infraestructuraElemento): bool
    {
        return $authUser->can('Restore:InfraestructuraElemento');
    }

    public function forceDelete(AuthUser $authUser, InfraestructuraElemento $infraestructuraElemento): bool
    {
        return $authUser->can('ForceDelete:InfraestructuraElemento');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InfraestructuraElemento');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InfraestructuraElemento');
    }

    public function replicate(AuthUser $authUser, InfraestructuraElemento $infraestructuraElemento): bool
    {
        return $authUser->can('Replicate:InfraestructuraElemento');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InfraestructuraElemento');
    }

}