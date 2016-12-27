<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectionRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'version',
        'system',
        'language',
        'hash',
        'name',
        'environment',
        'stack_trace',
        'message',
        'client_id',
        'client_ip',
        'project_id',
    ];

    /**
     * The project that this rule refers to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
