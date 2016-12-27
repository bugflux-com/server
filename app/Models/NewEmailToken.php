<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewEmailToken extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'new_email',
        'user_id',
        'token',
    ];

    /**
     * User who wants to change email.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Limit results to only active tokens.
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query) {
        $expire = config('profile.new_email_token.expire');
        return $query->where('updated_at', '>=', Carbon::now()->subMinutes($expire));
    }
}
