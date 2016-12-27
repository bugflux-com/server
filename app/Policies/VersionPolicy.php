<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Models\Version;
use Auth;
use DB;
use Illuminate\Auth\Access\HandlesAuthorization;

class VersionPolicy extends BasePolicy
{
    public function createAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProjectAs($user, $project->id, 'pm');
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

    public function create(User $user, Version $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }

    public function read(User $user, Version $model)
    {
        return $this->belongsToProject($user, $model->project_id);
    }

    public function update(User $user, Version $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }

    public function delete(User $user, Version $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }
}
