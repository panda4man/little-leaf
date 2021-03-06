<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deliverable extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'estimated_cost', 'estimated_hours', 'description', 'due_at', 'completed_at'];

    /**
     * @var array
     */
    protected $dates = ['due_at', 'completed_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function work()
    {
        return $this->morphMany(Work::class, 'workable');
    }
}
