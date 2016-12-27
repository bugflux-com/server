<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy extends BasePolicy
{
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

    public function create(User $user, Project $model)
    {
        return $this->createAny($user);
    }

    public function read(User $user, Project $model)
    {
        return $this->belongsToProject($user, $model->id);
    }

    public function update(User $user, Project $model)
    {
        return $this->belongsToProjectAs($user, $model->id, 'pm');
    }

    public function delete(User $user, Project $model)
    {
        return $this->deleteAny($user);
    }
}
