<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['name', 'estimated_cost', 'estimated_hours', 'due_at', 'completed_at'];

    /**
     * @var array
     */
    protected $dates = ['due_at', 'completed_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliverables()
    {
        return $this->hasMany(Deliverable::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function work()
    {
        return $this->morphMany(Work::class, 'workable');
    }
}
