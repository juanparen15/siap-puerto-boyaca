<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Recaudo;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecaudoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Recaudo');
    }

    public function view(AuthUser $authUser, Recaudo $recaudo): bool
    {
        return $authUser->can('View:Recaudo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Recaudo');
    }

    public function update(AuthUser $authUser, Recaudo $recaudo): bool
    {
        return $authUser->can('Update:Recaudo');
    }

    public function delete(AuthUser $authUser, Recaudo $recaudo): bool
    {
        return $authUser->can('Delete:Recaudo');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Recaudo');
    }

    public function restore(AuthUser $authUser, Recaudo $recaudo): bool
    {
        return $authUser->can('Restore:Recaudo');
    }

    public function forceDelete(AuthUser $authUser, Recaudo $recaudo): bool
    {
        return $authUser->can('ForceDelete:Recaudo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Recaudo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Recaudo');
    }

    public function replicate(AuthUser $authUser, Recaudo $recaudo): bool
    {
        return $authUser->can('Replicate:Recaudo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Recaudo');
    }

}