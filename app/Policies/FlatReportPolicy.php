<?php

namespace App\Policies;

use App\Models\FlatReport;
use App\Models\Project;
use App\Models\User;

class FlatReportPolicy extends BasePolicy
{
    public function createAny(User $user, $policy, Project $project)
    {
        return false;
    }

    public function readAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProject($user, $project->id);
    }

    public function updateAny(User $user, $policy, Project $project)
    {
        return false;
    }

    public function deleteAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProjectAsOneOf($user, $project->id, ['pm','dev']);
    }

    public function create(User $user, FlatReport $model)
    {
        return false;
    }

    public function read(User $user, FlatReport $model, Project $project)
    {
        return $this->belongsToProject($user, $project->id);
    }

    public function update(User $user, FlatReport $model, Project $project)
    {
        return false;
    }

    public function delete(User $user, FlatReport $model, Project $project)
    {
        return $this->belongsToProjectAsOneOf($user, $project->id, ['pm','dev']);
    }
    public function redo(User $user, FlatReport $model, Project $project)
    {
        return $this->belongsToProjectAsOneOf($user, $project->id, ['pm','dev']);
    }
}
