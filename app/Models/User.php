<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_blocked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_root'
    ];


    /**
     * The notifications types that user want to get.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notificationTypes()
    {
        return $this->belongsToMany(NotificationType::class)->withPivot('wantable_id', 'wantable_type', 'internal', 'email');
    }

    /**
     * The users that get this notification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notificationGroups()
    {
        return $this->hasMany(NotificationGroup::class);
    }

    /**
     * The permissions for given user
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function getPermissionsWithGroupAttribute()
    {
        static $permissions;
        if (empty($permissions)) {
            $permissions = $this->permissions()->with('group')->get();
        }

        return $permissions;
    }

    public function scopeCollaborators($query)
    {
        if($this->is_root) {
            return $query;
        }

        return $query->whereHas('permissions', function ($sub_query) {
            $sub_query->whereExists(function ($sub_sub_query) {
                $sub_sub_query->select('*')
                    ->from('permissions as my_permissions')
                    ->where('my_permissions.user_id', $this->getKey())
                    ->whereRaw('permissions.project_id = my_permissions.project_id');
            });
        });
    }

    public function getCollaboratorsAttribute()
    {
        static $collaborators;
        if (empty($collaborators)) {
            $collaborators = $this->newQuery()->collaborators()->get();
        }

        return $collaborators;
    }

    public function hasPhoto($size)
    {
        $path = "user_photos/$this->id-$size.jpg";

        return \Storage::exists($path);
    }
}
