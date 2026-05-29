<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InfraestructuraRed;
use Illuminate\Auth\Access\HandlesAuthorization;

class InfraestructuraRedPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InfraestructuraRed');
    }

    public function view(AuthUser $authUser, InfraestructuraRed $infraestructuraRed): bool
    {
        return $authUser->can('View:InfraestructuraRed');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InfraestructuraRed');
    }

    public function update(AuthUser $authUser, InfraestructuraRed $infraestructuraRed): bool
    {
        return $authUser->can('Update:InfraestructuraRed');
    }

    public function delete(AuthUser $authUser, InfraestructuraRed $infraestructuraRed): bool
    {
        return $authUser->can('Delete:InfraestructuraRed');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:InfraestructuraRed');
    }

    public function restore(AuthUser $authUser, InfraestructuraRed $infraestructuraRed): bool
    {
        return $authUser->can('Restore:InfraestructuraRed');
    }

    public function forceDelete(AuthUser $authUser, InfraestructuraRed $infraestructuraRed): bool
    {
        return $authUser->can('ForceDelete:InfraestructuraRed');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InfraestructuraRed');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InfraestructuraRed');
    }

    public function replicate(AuthUser $authUser, InfraestructuraRed $infraestructuraRed): bool
    {
        return $authUser->can('Replicate:InfraestructuraRed');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InfraestructuraRed');
    }

}