<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['description', 'hours'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function workable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
