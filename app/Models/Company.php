<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ModelHashId;
use Storage;

class Company extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'hash_id', 'name', 'address', 'city', 'state', 'zip', 'country', 'photo', 'default'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'default' => 'boolean'
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
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Handle automatic logic
     */
    public static function boot()
    {
        parent::boot();

        self::created(function ($company) {
            $company->hash_id = ModelHashId::encode($company->id);
            $company->save();
        });

        self::deleting(function ($company) {
             $path = $company->photo;

             if($path) {
                 Storage::delete($path);
             }
        });
    }
}
