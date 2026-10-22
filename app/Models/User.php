<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_approved',
        'available_days',
        'available_from',
        'available_to',
        'service_areas',
        'offered_categories',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_approved' => 'boolean',
    ];

    /**
     * Check if user is customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Check if user is service provider.
     */
    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check user role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if a provider has been approved by an admin.
     */
    public function isApproved(): bool
    {
        return (bool) $this->is_approved;
    }

    /**
     * Reviews received by this user (as a provider).
     */
    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    /**
     * Reviews written by this user (as a customer).
     */
    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    /**
     * Complaints filed by this user.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'customer_id');
    }

    /**
     * Average star rating this provider has received (0 when none).
     */
    public function averageRating(): float
    {
        return round((float) $this->reviewsReceived()->avg('rating'), 1);
    }

    /**
     * Total number of reviews received by this provider.
     */
    public function totalReviews(): int
    {
        return $this->reviewsReceived()->count();
    }

    /**
     * Household tasks created by this user.
     */
    public function householdTasks(): HasMany
    {
        return $this->hasMany(HouseholdTask::class, 'customer_id');
    }

    /**
     * Categories offered by this provider as an array of IDs.
     */
    public function offeredCategoryIds(): array
    {
        if (empty($this->offered_categories)) {
            return [];
        }
        return array_map('intval', explode(',', $this->offered_categories));
    }

    /**
     * Check if provider offers a specific category ID.
     */
    public function offersCategory($categoryId): bool
    {
        $ids = $this->offeredCategoryIds();
        return empty($ids) || in_array((int) $categoryId, $ids, true);
    }
}
