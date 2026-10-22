<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'description',
        'estimated_price',
    ];

    /**
     * Relationship to Bookings.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'category_id');
    }
}
