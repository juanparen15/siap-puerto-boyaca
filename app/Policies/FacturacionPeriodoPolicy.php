<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FacturacionPeriodo;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacturacionPeriodoPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FacturacionPeriodo');
    }

    public function view(AuthUser $authUser, FacturacionPeriodo $facturacionPeriodo): bool
    {
        return $authUser->can('View:FacturacionPeriodo');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FacturacionPeriodo');
    }

    public function update(AuthUser $authUser, FacturacionPeriodo $facturacionPeriodo): bool
    {
        return $authUser->can('Update:FacturacionPeriodo');
    }

    public function delete(AuthUser $authUser, FacturacionPeriodo $facturacionPeriodo): bool
    {
        return $authUser->can('Delete:FacturacionPeriodo');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:FacturacionPeriodo');
    }

    public function restore(AuthUser $authUser, FacturacionPeriodo $facturacionPeriodo): bool
    {
        return $authUser->can('Restore:FacturacionPeriodo');
    }

    public function forceDelete(AuthUser $authUser, FacturacionPeriodo $facturacionPeriodo): bool
    {
        return $authUser->can('ForceDelete:FacturacionPeriodo');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FacturacionPeriodo');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FacturacionPeriodo');
    }

    public function replicate(AuthUser $authUser, FacturacionPeriodo $facturacionPeriodo): bool
    {
        return $authUser->can('Replicate:FacturacionPeriodo');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FacturacionPeriodo');
    }

}