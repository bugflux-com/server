<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTypeUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_type_user';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notification_type_id',
        'user_id',
        'wantable_id', 'wantable_type',
        'internal', 'email'
    ];

    /**
     * Get all of the owning wantable models.
     */
    public function wantable()
    {
        return $this->morphTo();
    }
}
