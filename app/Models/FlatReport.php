<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FlatReport
 *
 * @package App\Models
 * @mixin \Eloquent
 */
class FlatReport extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project', 'version', 'system', 'language', 'error_id', 'hash', 'name',
        'environment', 'stack_trace', 'message', 'client_id', 'client_ip'
    ];

    /**
     * The processed report entity (or null if process failed).
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function report()
    {
        return $this->hasOne(Report::class);
    }

    /**
     * Update imported_at timestamp.
     *
     * @return bool
     */
    public function touchImported()
    {
        $this->imported_at = $this->freshTimestamp();
        return $this->save();
    }
}
