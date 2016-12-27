<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'group_id', 'project_id'];

    /**
     * The user for given permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The group for given permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * The project for given permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check whether given user has access to the project with a specific role.
     *
     * @param User|int $user User model or id.
     * @param Project|int $project Project model or id.
     * @param Group|int $group Group model or id.
     * @return bool
     */
    public function auth($user, $project, $group)
    {
        $user_id = $user instanceof User ? $user->getKey() : $user;
        $project_id = $project instanceof Project ? $project->getKey() : $project;
        $group_id = $group instanceof Group ? $group->getKey() : $group;

        return Permission::where('user_id', $user_id)
            ->where('group_id', $project_id)
            ->where('project_id', $group_id)
            ->exists();
    }


}
