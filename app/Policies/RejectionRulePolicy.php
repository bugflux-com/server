<?php

namespace App\Policies;

use App\Models\RejectionRule;
use App\Models\Project;
use App\Models\User;

class RejectionRulePolicy extends BasePolicy
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

    public function create(User $user, RejectionRule $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }

    public function read(User $user, RejectionRule $model)
    {
        return $this->belongsToProject($user, $model->project_id);
    }

    public function update(User $user, RejectionRule $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }

    public function delete(User $user, RejectionRule $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }
}
