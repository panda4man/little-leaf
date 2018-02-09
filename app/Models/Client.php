<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ModelHashId;

class Client extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'hash_id', 'name', 'address', 'city', 'state', 'zip', 'country'
    ];

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hash_id';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Handle automatic logic
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($client) {
            $client->hash_id = ModelHashId::encode($client->id);
        });
    }
}
