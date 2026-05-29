<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Pqrs;
use Illuminate\Auth\Access\HandlesAuthorization;

class PqrsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Pqrs');
    }

    public function view(AuthUser $authUser, Pqrs $pqrs): bool
    {
        return $authUser->can('View:Pqrs');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Pqrs');
    }

    public function update(AuthUser $authUser, Pqrs $pqrs): bool
    {
        return $authUser->can('Update:Pqrs');
    }

    public function delete(AuthUser $authUser, Pqrs $pqrs): bool
    {
        return $authUser->can('Delete:Pqrs');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Pqrs');
    }

    public function restore(AuthUser $authUser, Pqrs $pqrs): bool
    {
        return $authUser->can('Restore:Pqrs');
    }

    public function forceDelete(AuthUser $authUser, Pqrs $pqrs): bool
    {
        return $authUser->can('ForceDelete:Pqrs');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Pqrs');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Pqrs');
    }

    public function replicate(AuthUser $authUser, Pqrs $pqrs): bool
    {
        return $authUser->can('Replicate:Pqrs');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Pqrs');
    }

}