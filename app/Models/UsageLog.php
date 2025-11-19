<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageLog extends Model
{
    protected $fillable = [
        'user_id',
        'addon_slug',
        'action',
        'tool',
        'metadata',
        'ip_address',
        'user_agent',
        'success',
        'error_message',
    ];

    protected $casts = [
        'metadata' => 'array',
        'success' => 'boolean',
    ];

    /**
     * Get the user that owns the usage log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a successful action.
     */
    public static function logSuccess(int $userId, string $addonSlug, string $action, string $tool = null, array $metadata = []): self
    {
        return static::create([
            'user_id' => $userId,
            'addon_slug' => $addonSlug,
            'action' => $action,
            'tool' => $tool,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'success' => true,
        ]);
    }

    /**
     * Log a failed action.
     */
    public static function logError(int $userId, string $addonSlug, string $action, string $errorMessage, string $tool = null, array $metadata = []): self
    {
        return static::create([
            'user_id' => $userId,
            'addon_slug' => $addonSlug,
            'action' => $action,
            'tool' => $tool,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'success' => false,
            'error_message' => $error_message,
        ]);
    }
}
