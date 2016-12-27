<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\Project;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy extends BasePolicy
{
    public function createAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProjectAs($user, $project->id, 'pm');
    }

    public function createAnyPermission(User $user)
    {
        return false;
    }

    public function readAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProject($user, $project->id);
    }

    public function updateAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProjectAs($user, $project->id, 'pm');
    }

    public function deleteAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProjectAs($user, $project->id, 'pm');
    }

    public function create(User $user, Permission $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }

    public function read(User $user, Permission $model)
    {
        return $this->belongsToProject($user, $model->project_id);
    }

    public function update(User $user, Permission $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }

    public function delete(User $user, Permission $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }
}
