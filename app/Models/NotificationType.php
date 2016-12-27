<?php

namespace App\Models;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $fillable = [
        'code',
    ];

    /**
     * The notifications of this type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * The users that want notifications of this type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('wantable_id', 'wantable_type', 'internal', 'email');
    }

}
