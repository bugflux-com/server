<?php

namespace App\Policies;

use App\Models\Error;
use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy extends BasePolicy
{
    public function createAny(User $user, $policy, Error $error)
    {
        return false;
    }

    public function readAny(User $user, $policy, Error $error)
    {
        return $this->belongsToProject($user, $error->project_id);
    }

    public function updateAny(User $user, $policy, Error $error)
    {
        return $this->belongsToProjectAs($user, $error->project_id, 'pm');
    }

    public function deleteAny(User $user, $policy, Error $error)
    {
        return $this->belongsToProjectAs($user, $error->project_id, 'pm');
    }

    public function create(User $user, Report $model)
    {
        return false;
    }

    public function read(User $user, Report $model)
    {
        return $this->belongsToProject($user, $model->error->project_id);
    }

    public function update(User $user, Report $model)
    {
        return $this->belongsToProjectAs($user, $model->error->project_id, 'pm');
    }

    public function delete(User $user, Report $model)
    {
        return $this->belongsToProjectAs($user, $model->error->project_id, 'pm');
    }
}
