<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name', 'address', 'city', 'state', 'zip', 'country'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
