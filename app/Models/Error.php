<?php

namespace App\Models;

use App\Models\Traits\HasManyCountRelation;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Error extends Model
{
    use HasManyCountRelation;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash', 'name', 'project_id', 'environment_id'
    ];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['project'];

    /**
     * The project in which error occurs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * The environment in which error occurs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }

    /**
     * Reports related to the error.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Tags assigned to the error.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get all of the error's comments.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get all of the error's notification type user rows.
     */
    public function wanted_notification_type_users()
    {
        return $this->morphMany(NotificationTypeUser::class, 'wantable');
    }

    /**
     * Get unique clients count relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clientsCount()
    {
        return $this->hasManyCount(Report::class, 'distinct client_id');
    }

    /**
     * Get count of unique clients who send a report(s) about this error.
     *
     * @return int
     */
    public function getClientsCountAttribute()
    {
        return $this->hasManyCountAttribute('clientsCount');
    }

    public function reportsTrend()
    {
        $reportedAtKey = 'reported_at_date';
        $foreignKey = $this->reports()->getForeignKey();

        return $this->reports()
            ->selectRaw("$foreignKey, $reportedAtKey, count(*) as aggregate")
            ->groupBy([$foreignKey, $reportedAtKey])
            ->orderBy($reportedAtKey);
    }

    public function scopeWithReportsTrend($query, $trend_for_days)
    {
        return $query->with(['reportsTrend' => function ($query) use ($trend_for_days) {
            $query->lastNDays($trend_for_days);
        }]);
    }

    public function scopeFilteredEnvironment($query, $env_filter)
    {
        if($env_filter == null) {
            return $query;
        }

        $env_id = Environment::where('name', $env_filter)->firstOrFail()->id;
        return $query->where('environment_id', $env_id);
    }
    
    public function scopeFilteredTag($query, $tag_id)
    {
        if($tag_id == -1) {
            return $query;
        }

        if($tag_id == -2) {
            return $query->has('tags');
        }

        if($tag_id == -3) {
            return $query->has('tags', '==', 0);
        }

        return  $query->whereHas('tags', function($q) use ($tag_id) {
            $q->where('tag_id', $tag_id);
        });
    }

}
