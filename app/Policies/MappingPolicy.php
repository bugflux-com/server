<?php

namespace App\Policies;

use App\Models\Mapping;
use App\Models\Project;
use App\Models\User;
use App\Models\Version;
use Auth;
use DB;
use Illuminate\Auth\Access\HandlesAuthorization;

class MappingPolicy extends BasePolicy
{
    public function createAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProjectAsOneOf($user, $project->id, ['pm','dev','tester']);
    }

    public function readAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProject($user, $project->id);
    }

    public function updateAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProjectAsOneOf($user, $project->id, ['pm','dev','tester']);
    }

    public function deleteAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProjectAsOneOf($user, $project->id, ['pm','dev','tester']);
    }

    public function create(User $user, Mapping $model)
    {
        return $this->belongsToProjectAsOneOf($user, $model->project_id, ['pm','dev','tester']);
    }

    public function read(User $user, Mapping $model)
    {
        return $this->belongsToProject($user, $model->project_id);
    }

    public function update(User $user, Mapping $model)
    {
        return $this->belongsToProjectAsOneOf($user, $model->project_id, ['pm','dev','tester']);
    }

    public function delete(User $user, Mapping $model)
    {
        return $this->belongsToProjectAsOneOf($user, $model->project_id, ['pm','dev','tester']);
    }
}
