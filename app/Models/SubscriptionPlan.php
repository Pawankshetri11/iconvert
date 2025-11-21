<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'billing_cycle',
        'max_files_per_conversion',
        'max_conversions_per_month',
        'included_addons',
        'features',
        'is_active',
        'is_popular',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'included_addons' => 'array',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];

    /**
     * Get the subscriptions for this plan.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope for active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    /**
     * Check if addon is included in this plan.
     */
    public function hasAddon(string $addonSlug): bool
    {
        return in_array($addonSlug, $this->included_addons ?? []);
    }

    /**
     * Get formatted price with currency.
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    /**
     * Get conversions limit text.
     */
    public function getConversionsTextAttribute(): string
    {
        if ($this->max_conversions_per_month === 0) {
            return 'Unlimited';
        }
        return $this->max_conversions_per_month . ' per month';
    }

    /**
     * Get files limit text.
     */
    public function getFilesTextAttribute(): string
    {
        return $this->max_files_per_conversion . ' files per conversion';
    }
}
