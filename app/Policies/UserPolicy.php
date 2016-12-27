<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function createAny(User $user)
    {
        return false;
    }

    public function readAny(User $user)
    {
        return true;
    }

    public function updateAny(User $user)
    {
        return false;
    }

    public function deleteAny(User $user)
    {
        return false;
    }

    public function create(User $user, User $model)
    {
        return false;
    }

    public function read(User $user, User $model)
    {
        return $this->isCollaboratorWith($user, $model->id);
    }

    public function update(User $user, User $model)
    {
        return false;
    }

    public function delete(User $user, User $model)
    {
        return false;
    }
}
