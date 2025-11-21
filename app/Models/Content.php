<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Content extends Model
{
    protected $fillable = [
        'key',
        'group',
        'type',
        'value',
        'label',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active content.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific group.
     */
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get content value by key with caching.
     */
    public static function getValue(string $key, $default = '')
    {
        $cacheKey = "content_{$key}";

        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $content = static::where('key', $key)->active()->first();
            return $content ? $content->value : $default;
        });
    }

    /**
     * Get content by group with caching.
     */
    public static function getGroup(string $group)
    {
        $cacheKey = "content_group_{$group}";

        return Cache::remember($cacheKey, 3600, function () use ($group) {
            return static::group($group)->active()->get()->keyBy('key');
        });
    }

    /**
     * Clear content cache.
     */
    public static function clearCache()
    {
        Cache::flush(); // For simplicity, flush all cache
    }

    /**
     * Get available groups.
     */
    public static function getGroups()
    {
        return [
            'header' => 'Header',
            'footer' => 'Footer',
            'landing' => 'Landing Page',
            'pricing' => 'Pricing Page',
            'general' => 'General',
        ];
    }

    /**
     * Get available types.
     */
    public static function getTypes()
    {
        return [
            'text' => 'Plain Text',
            'html' => 'HTML',
            'json' => 'JSON',
        ];
    }
}
