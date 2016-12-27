<?php

namespace App\Policies;

use App\Models\Error;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ErrorPolicy extends BasePolicy
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
        return $this->belongsToProjectAsOneOf($user, $project->id, ['pm','dev']);
    }

    public function deleteAny(User $user, $policy, Project $project)
    {
        return $this->belongsToProjectAs($user, $project->id, 'pm');
    }

    public function create(User $user, Error $model)
    {
        return false;
    }

    public function read(User $user, Error $model)
    {
        return $this->belongsToProject($user, $model->project_id);
    }

    public function update(User $user, Error $model)
    {
        return $this->belongsToProjectAsOneOf($user, $model->project_id, ['pm','dev']);
    }

    public function delete(User $user, Error $model)
    {
        return $this->belongsToProjectAs($user, $model->project_id, 'pm');
    }

    public function connectWithTag(User $user, Error $model)
    {
        return $this->belongsToProjectAsOneOf($user, $model->project_id, ['pm','dev','tester']);
    }

    public function disconnectWithTag(User $user, Error $model)
    {
        return $this->belongsToProjectAsOneOf($user, $model->project_id, ['pm','dev','tester']);
    }
}
