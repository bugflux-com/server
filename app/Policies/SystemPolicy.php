<?php

namespace App\Policies;

use App\Models\System;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemPolicy extends BasePolicy
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

    public function create(User $user, System $model)
    {
        return false;
    }

    public function read(User $user, System $model)
    {
        return true;
    }

    public function update(User $user, System $model)
    {
        return false;
    }

    public function delete(User $user, System $model)
    {
        return false;
    }
}
