<?php

namespace App\Models;

use App\Models\Traits\HasManyCountRelation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'stack_trace', 'message',
        'version_id', 'client_id', 'client_ip',
        'error_id', 'language_id', 'system_id',
        'reported_at', 'reported_at_date', 'flat_report_id'
    ];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['error'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'reported_at' => 'datetime',
        'reported_at_date' => 'date',
    ];

    /**
     * The error covered by the report.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function error()
    {
        return $this->belongsTo(Error::class);
    }

    /**
     * The app language in which the error occurred.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * The operating system in which the error occurred.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function system()
    {
        return $this->belongsTo(System::class);
    }

    /**
     * The application version in which the error occurred.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function version()
    {
        return $this->belongsTo(Version::class);
    }

    /**
     * The original report message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function flatReport()
    {
        return $this->belongsTo(FlatReport::class);
    }

    /**
     * Get all of the report's comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get all of the report's notification type user rows.
     */
    public function wanted_notification_type_users()
    {
        return $this->morphMany(NotificationTypeUser::class, 'wantable');
    }

    public function scopeLastNDays($query, $days)
    {
        return $query->where('reported_at', '>=', Carbon::today()->subDays($days))
            ->where('reported_at', '<', Carbon::today());
    }

    public function getReportedAtDateStringAttribute()
    {
        return $this->reported_at_date->toDateString();
    }

    public function scopeFilteredSystem($query, $system_id)
    {
        if($system_id == -1) {
            return $query;
        }

        return $query->where('system_id', $system_id);
    }

    public function scopeFilteredLanguage($query, $language_id)
    {
        if($language_id == -1) {
            return $query;
        }

        return $query->where('language_id', $language_id);
    }

    public function scopeFilteredVersion($query, $version_id)
    {
        if($version_id == -1) {
            return $query;
        }

        return $query->where('version_id', $version_id);
    }
    
}
