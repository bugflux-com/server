<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'json',
        'notificable_id',
        'notificable_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'json' => 'object',
    ];

    /**
     * The groups of this notification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(NotificationGroup::class);
    }

    /**
     * Get all of the owning notificable models.
     */
    public function notificable()
    {
        return $this->morphTo();
    }
}
