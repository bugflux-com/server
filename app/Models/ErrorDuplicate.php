<?php

namespace App\Models;

use App\Models\Traits\HasManyCountRelation;
use Illuminate\Database\Eloquent\Model;

class ErrorDuplicate extends Model
{
    use HasManyCountRelation;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'error_id'
    ];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['error'];

    /**
     * The error in which duplicate occurs.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function error()
    {
        return $this->belongsTo(Error::class);
    }

    /**
     * Helper to generate project codes
     *
     * @param $length
     * @param string $keyspace
     * @return string
     */
    public static function random_code($length = 8, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

    public function scopeDuplicatesOf($query, $error_id)
    {
        $duplicate_code = ErrorDuplicate::where('error_id', $error_id)->value('code');

        return $query->where('code', $duplicate_code)
            ->where('error_id', '!=', $error_id);
    }
}
