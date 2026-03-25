<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'external_id',
        'name',
        'iso2',
        'iso3',
        'phone_code',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
