<?php

namespace App\Models;

use App\Models\RejectionRule;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code'
    ];

    /**
     * Errors which occurs in the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function errors()
    {
        return $this->hasMany(Error::class);
    }

    /**
     * Last created error.
     *
     * @return mixed
     */
    public function latestError()
    {
        return $this->hasOne(Error::class)->latest();
    }

    /**
     * All reports in all errors.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function reports()
    {
        return $this->hasManyThrough(Report::class, Error::class);
    }

    /**
     * Tags which occurs in the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * The versions that have been released.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function versions()
    {
        return $this->hasMany(Version::class);
    }

    /**
     * The permissions for given project
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Helper to generate project codes
     *
     * @param $length
     * @param string $keyspace
     * @return string
     */
    public static function random_code($length = 8, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyz')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

    /**
     * The mappings for given project
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function mappings()
    {
        return $this->hasMany(Mapping::class);
    }

    /**
     * The rejection rules for given project
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function rejectionRules()
    {
        return $this->hasMany(RejectionRule::class);
    }

    public function scopeAvailableForCurrentUser($query)
    {
        if(Auth::user()->is_root) { // FIXME: policy logic in model
            return $query;
        }

        return $query->whereHas('permissions', function($q) {
            $q->where('user_id', Auth::user()->id);
        });
    }

    public function reportsTrend()
    {
        $reportedAtKey = 'reported_at_date';

        return $this->reports()
            ->selectRaw("$reportedAtKey, count(*) as aggregate")
            ->groupBy([$reportedAtKey])
            ->orderBy($reportedAtKey);
    }

    public function scopeWithReportsTrend($query, $trend_for_days)
    {
        return $query->with(['reportsTrend' => function ($query) use ($trend_for_days) {
            $query->lastNDays($trend_for_days);
        }]);
    }

    public function reportsPerVersion()
    {
        return $this->reports()
            ->with('version')
            ->selectRaw('version_id, count(*) as aggregate')
            ->groupBy('version_id')
            ->orderBy('aggregate', 'desc');
    }

    public function scopeWithReportsPerVersion($query, $versions_limit)
    {
        return $query->with(['reportsPerVersion' => function ($query) use ($versions_limit) {
            $query->limit($versions_limit);
        }]);
    }

    public function reportsPerSystem()
    {
        return $this->reports()
            ->with('system')
            ->selectRaw('system_id, count(*) as aggregate')
            ->groupBy('system_id')
            ->orderBy('aggregate', 'desc');
    }

    public function scopeWithReportsPerSystem($query, $systems_limit)
    {
        return $query->with(['reportsPerSystem' => function ($query) use ($systems_limit) {
            $query->limit($systems_limit);
        }]);
    }

    public function reportsPerLanguage()
    {
        return $this->reports()
            ->with('language')
            ->selectRaw('language_id, count(*) as aggregate')
            ->groupBy('language_id')
            ->orderBy('aggregate', 'desc');
    }

    public function scopeWithReportsPerLanguage($query, $langs_limit)
    {
        return $query->with(['reportsPerLanguage' => function ($query) use ($langs_limit) {
            $query->limit($langs_limit);
        }]);
    }
}
