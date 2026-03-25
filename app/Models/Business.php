<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $table = 'business';

    protected $fillable = [
        'contact_number',
        'business_name',
        'owner_id',
        'contact_person',
        'hide_address',
        'business_email',
        'business_contact_number',
        'website',
        'business_description',
        'business_logo',
        'cover_photo',
        'country',
        'state',
        'city',
        'pincode',
        'address_line_1',
        'industry_id',
        'category_id',
        'service_id',
        'tags',
        'hear_from',
        'sunday_timing',
        'monday_timing',
        'tuesday_timing',
        'wednesday_timing',
        'thursday_timing',
        'friday_timing',
        'saturday_timing',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'business_id');
    }
}
