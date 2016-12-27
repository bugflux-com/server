<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy extends BasePolicy
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

    public function create(User $user, Tag $model)
    {
        return $this->belongsToProjectAsOneOf($user, $model->project_id, ['pm','dev','tester']);
    }

    public function read(User $user, Tag $model)
    {
        return $this->belongsToProject($user, $model->project_id);
    }

    public function update(User $user, Tag $model)
    {
        return $this->belongsToProjectAsOneOf($user, $model->project_id, ['pm','dev','tester']);
    }

    public function delete(User $user, Tag $model)
    {
        return $this->belongsToProjectAsOneOf($user, $model->project_id, ['pm','dev','tester']);
    }


}
