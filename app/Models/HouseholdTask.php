<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseholdTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'task_title',
        'description',
        'reminder_date',
        'reminder_time',
        'priority',
        'status',
    ];

    /**
     * Relationship to the Customer user.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
