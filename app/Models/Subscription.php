<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_name',
        'price',
        'currency',
        'billing_cycle',
        'conversion_limit',
        'enabled_addons',
        'starts_at',
        'ends_at',
        'status',
        'payment_method',
        'payment_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'enabled_addons' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if subscription is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' &&
               ($this->ends_at === null || $this->ends_at->isFuture());
    }

    /**
     * Check if subscription has expired.
     */
    public function isExpired(): bool
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    /**
     * Check if addon is enabled for this subscription.
     */
    public function hasAddon(string $addonSlug): bool
    {
        $enabledAddons = $this->enabled_addons ?? [];
        return in_array($addonSlug, $enabledAddons);
    }

    /**
     * Get remaining conversions for this subscription.
     */
    public function getRemainingConversions(): int
    {
        if ($this->conversion_limit === 0) {
            return -1; // Unlimited
        }

        $usedConversions = $this->user->usageLogs()
            ->where('created_at', '>=', $this->starts_at)
            ->where('success', true)
            ->count();

        return max(0, $this->conversion_limit - $usedConversions);
    }
}
