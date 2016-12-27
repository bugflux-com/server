<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy extends BasePolicy
{
    public function createAny(User $user, $policy, $project_id)
    {
        return $this->belongsToProject($user, $project_id);
    }

    public function readAny(User $user, $policy, $project_id)
    {
        return $this->belongsToProject($user, $project_id);
    }

    public function updateAny(User $user, $policy, $project_id)
    {
        return false;
    }

    public function deleteAny(User $user, $policy, $project_id)
    {
        return false;
    }

    public function create(User $user, Comment $model, $project_id)
    {
        return $this->belongsToProject($user, $project_id);
    }

    public function read(User $user, Comment $model, $project_id)
    {
        return $this->belongsToProject($user, $project_id);
    }

    public function update(User $user, Comment $model, $project_id)
    {
        return $model->user->id == $user->id
            && $model->created_at->addHour()->gte(Carbon::now())
            && $this->belongsToProject($user, $project_id);
    }

    public function delete(User $user, Comment $model, $project_id)
    {
        return $model->user->id == $user->id
            && $model->created_at->addHour()->gte(Carbon::now())
            && $this->belongsToProject($user, $project_id);
    }
}
