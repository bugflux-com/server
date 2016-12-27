<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if($user->is_root) {
            return true;
        }
    }

    protected function belongsToProjectAs($user, $project_id, $group_tag)
    {
        return $user->permissionsWithGroup
            ->whereLoose('project_id', $project_id)
            ->contains('group.tag', $group_tag);
    }

    protected function belongsToProjectAsOneOf($user, $project_id, $group_tags)
    {
        return !$user->permissionsWithGroup
            ->whereLoose('project_id', $project_id)
            ->whereInLoose('group.tag', $group_tags)
            ->isEmpty();
    }

    protected function belongsToProject($user, $project_id) {
        return !$user->permissionsWithGroup
            ->whereLoose('project_id', $project_id)
            ->isEmpty();
    }

    protected function isCollaboratorWith($user, $user_id) {
        return $user->collaborators
            ->contains('id', $user_id);
    }
}
