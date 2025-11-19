<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the user's active subscription.
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active');
    }

    /**
     * Get the user's usage logs.
     */
    public function usageLogs()
    {
        return $this->hasMany(UsageLog::class);
    }

    /**
     * Check if user has access to a specific addon.
     */
    public function hasAddonAccess(string $addonSlug): bool
    {
        $subscription = $this->activeSubscription;

        if (!$subscription) {
            return false; // No active subscription
        }

        // Free plan has access to PDF converter only
        if ($subscription->plan_name === 'Free') {
            return $addonSlug === 'pdf-converter';
        }

        // Check if addon is enabled in subscription
        $enabledAddons = $subscription->enabled_addons ?? [];
        return in_array($addonSlug, $enabledAddons);
    }

    /**
     * Check if user has remaining conversions.
     */
    public function hasConversionLimit(): bool
    {
        $subscription = $this->activeSubscription;
        return $subscription && $subscription->conversion_limit > 0;
    }

    /**
     * Get remaining conversions for user.
     */
    public function getRemainingConversions(): int
    {
        $subscription = $this->activeSubscription;

        if (!$subscription) {
            return 0;
        }

        if ($subscription->conversion_limit === 0) {
            return -1; // Unlimited
        }

        $usedConversions = $this->usageLogs()
            ->where('created_at', '>=', $subscription->starts_at)
            ->where('success', true)
            ->count();

        return max(0, $subscription->conversion_limit - $usedConversions);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
