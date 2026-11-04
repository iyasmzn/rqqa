<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Ebook;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class EbookPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Ebook');
    }

    public function view(AuthUser $authUser, Ebook $ebook): bool
    {
        return $authUser->can('View:Ebook');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Ebook');
    }

    public function update(AuthUser $authUser, Ebook $ebook): bool
    {
        return $authUser->can('Update:Ebook');
    }

    public function delete(AuthUser $authUser, Ebook $ebook): bool
    {
        return $authUser->can('Delete:Ebook');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Ebook');
    }

    public function restore(AuthUser $authUser, Ebook $ebook): bool
    {
        return $authUser->can('Restore:Ebook');
    }

    public function forceDelete(AuthUser $authUser, Ebook $ebook): bool
    {
        return $authUser->can('ForceDelete:Ebook');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Ebook');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Ebook');
    }

    public function replicate(AuthUser $authUser, Ebook $ebook): bool
    {
        return $authUser->can('Replicate:Ebook');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Ebook');
    }
}
