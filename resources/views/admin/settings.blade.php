@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">System Settings</h2>
    <p style="color: #b0b0b0;">Configure application settings and system preferences</p>
</div>

<!-- Application Settings -->
<div class="stat-card" style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Application Settings</h3>

    <form method="POST" action="{{ route('admin.update-settings') }}">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">Application Name</label>
                <input type="text" name="app_name" value="{{ $settings['app_name'] }}" style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; color: #e0e0e0; font-size: 1rem;" required>
            </div>

            <div>
                <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">Debug Mode</label>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #e0e0e0;">
                        <input type="radio" name="app_debug" value="1" {{ $settings['app_debug'] ? 'checked' : '' }}>
                        <span>Enabled</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #e0e0e0;">
                        <input type="radio" name="app_debug" value="0" {{ !$settings['app_debug'] ? 'checked' : '' }}>
                        <span>Disabled</span>
                    </label>
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" style="background: linear-gradient(45deg, #ffd700, #ffed4e); color: #000; border: none; padding: 0.75rem 2rem; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                Save Settings
            </button>
        </div>
    </form>
</div>

<!-- Payment Gateway Settings -->
<div class="stat-card" style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Payment Gateway Settings</h3>

    <form method="POST" action="{{ route('admin.update-payment-gateways') }}">
        @csrf

        <!-- Razorpay -->
        <div style="margin-bottom: 2rem; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 8px;">
            <h4 style="color: #ffd700; font-weight: 600; margin-bottom: 1rem;">Razorpay</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">API Key</label>
                    <input type="text" name="razorpay_key" value="{{ config('services.razorpay.key') }}" style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; color: #e0e0e0; font-size: 1rem;">
                </div>
                <div>
                    <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">Secret Key</label>
                    <input type="password" name="razorpay_secret" value="{{ config('services.razorpay.secret') }}" style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; color: #e0e0e0; font-size: 1rem;">
                </div>
            </div>
        </div>

        <!-- Cashfree -->
        <div style="margin-bottom: 2rem; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 8px;">
            <h4 style="color: #ffd700; font-weight: 600; margin-bottom: 1rem;">Cashfree</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">App ID</label>
                    <input type="text" name="cashfree_app_id" value="{{ config('services.cashfree.app_id') }}" style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; color: #e0e0e0; font-size: 1rem;">
                </div>
                <div>
                    <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">Secret Key</label>
                    <input type="password" name="cashfree_secret" value="{{ config('services.cashfree.secret') }}" style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; color: #e0e0e0; font-size: 1rem;">
                </div>
            </div>
        </div>

        <!-- Stripe -->
        <div style="margin-bottom: 2rem; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 8px;">
            <h4 style="color: #ffd700; font-weight: 600; margin-bottom: 1rem;">Stripe</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">Publishable Key</label>
                    <input type="text" name="stripe_key" value="{{ config('services.stripe.key') }}" style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; color: #e0e0e0; font-size: 1rem;">
                </div>
                <div>
                    <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">Secret Key</label>
                    <input type="password" name="stripe_secret" value="{{ config('services.stripe.secret') }}" style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; color: #e0e0e0; font-size: 1rem;">
                </div>
            </div>
        </div>

        <!-- PayPal -->
        <div style="margin-bottom: 2rem; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 8px;">
            <h4 style="color: #ffd700; font-weight: 600; margin-bottom: 1rem;">PayPal</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">Client ID</label>
                    <input type="text" name="paypal_client_id" value="{{ config('services.paypal.client_id') }}" style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; color: #e0e0e0; font-size: 1rem;">
                </div>
                <div>
                    <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">Client Secret</label>
                    <input type="password" name="paypal_client_secret" value="{{ config('services.paypal.client_secret') }}" style="width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 215, 0, 0.3); border-radius: 8px; color: #e0e0e0; font-size: 1rem;">
                </div>
            </div>
            <div style="margin-top: 1rem;">
                <label style="display: block; color: #e0e0e0; font-weight: 500; margin-bottom: 0.5rem;">Mode</label>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #e0e0e0;">
                        <input type="radio" name="paypal_mode" value="sandbox" {{ config('services.paypal.mode') === 'sandbox' ? 'checked' : '' }}>
                        <span>Sandbox</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #e0e0e0;">
                        <input type="radio" name="paypal_mode" value="live" {{ config('services.paypal.mode') === 'live' ? 'checked' : '' }}>
                        <span>Live</span>
                    </label>
                </div>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" style="background: linear-gradient(45deg, #ffd700, #ffed4e); color: #000; border: none; padding: 0.75rem 2rem; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                Save Payment Settings
            </button>
        </div>
    </form>
</div>

<!-- System Information -->
<div class="stat-card" style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">System Information</h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        <div style="background: rgba(255, 255, 255, 0.05); padding: 1rem; border-radius: 8px;">
            <div style="color: #b0b0b0; font-size: 0.9rem; margin-bottom: 0.5rem;">PHP Version</div>
            <div style="color: #ffd700; font-weight: 600; font-size: 1.1rem;">{{ PHP_VERSION }}</div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.05); padding: 1rem; border-radius: 8px;">
            <div style="color: #b0b0b0; font-size: 0.9rem; margin-bottom: 0.5rem;">Laravel Version</div>
            <div style="color: #ffd700; font-weight: 600; font-size: 1.1rem;">{{ app()->version() }}</div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.05); padding: 1rem; border-radius: 8px;">
            <div style="color: #b0b0b0; font-size: 0.9rem; margin-bottom: 0.5rem;">Database</div>
            <div style="color: #ffd700; font-weight: 600; font-size: 1.1rem;">{{ config('database.default') }}</div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.05); padding: 1rem; border-radius: 8px;">
            <div style="color: #b0b0b0; font-size: 0.9rem; margin-bottom: 0.5rem;">Cache Driver</div>
            <div style="color: #ffd700; font-weight: 600; font-size: 1.1rem;">{{ config('cache.default') }}</div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.05); padding: 1rem; border-radius: 8px;">
            <div style="color: #b0b0b0; font-size: 0.9rem; margin-bottom: 0.5rem;">Session Driver</div>
            <div style="color: #ffd700; font-weight: 600; font-size: 1.1rem;">{{ config('session.driver') }}</div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.05); padding: 1rem; border-radius: 8px;">
            <div style="color: #b0b0b0; font-size: 0.9rem; margin-bottom: 0.5rem;">Mail Driver</div>
            <div style="color: #ffd700; font-weight: 600; font-size: 1.1rem;">{{ config('mail.default') }}</div>
        </div>
    </div>
</div>

<!-- Cache Management -->
<div class="stat-card" style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Cache Management</h3>
    <p style="color: #b0b0b0; margin-bottom: 1rem;">Clear various caches to ensure fresh data</p>

    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <form method="POST" action="{{ route('admin.clear-cache') }}" style="display: inline;">
            @csrf
            <button type="submit" style="background: linear-gradient(45deg, #ef4444, #dc2626); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 25px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                Clear All Cache
            </button>
        </form>

        <button onclick="clearRouteCache()" style="background: linear-gradient(45deg, #f59e0b, #d97706); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 25px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            Clear Route Cache
        </button>

        <button onclick="clearViewCache()" style="background: linear-gradient(45deg, #10b981, #059669); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 25px; font-weight: 500; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            Clear View Cache
        </button>
    </div>
</div>

<!-- Environment Variables -->
<div class="stat-card">
    <h3 style="font-size: 1.25rem; font-weight: 600; color: #ffd700; margin-bottom: 1rem;">Environment Configuration</h3>
    <p style="color: #b0b0b0; margin-bottom: 1rem;">Current environment settings</p>

    <div style="background: rgba(0, 0, 0, 0.5); border-radius: 8px; padding: 1rem; font-family: 'Courier New', monospace; font-size: 0.9rem; overflow-x: auto;">
        <div style="margin-bottom: 0.5rem;"><span style="color: #22c55e;">APP_NAME</span>=<span style="color: #ffd700;">{{ config('app.name') }}</span></div>
        <div style="margin-bottom: 0.5rem;"><span style="color: #22c55e;">APP_ENV</span>=<span style="color: #ffd700;">{{ config('app.env') }}</span></div>
        <div style="margin-bottom: 0.5rem;"><span style="color: #22c55e;">APP_DEBUG</span>=<span style="color: #ffd700;">{{ config('app.debug') ? 'true' : 'false' }}</span></div>
        <div style="margin-bottom: 0.5rem;"><span style="color: #22c55e;">DB_CONNECTION</span>=<span style="color: #ffd700;">{{ config('database.default') }}</span></div>
        <div style="margin-bottom: 0.5rem;"><span style="color: #22c55e;">CACHE_DRIVER</span>=<span style="color: #ffd700;">{{ config('cache.default') }}</span></div>
        <div style="margin-bottom: 0.5rem;"><span style="color: #22c55e;">SESSION_DRIVER</span>=<span style="color: #ffd700;">{{ config('session.driver') }}</span></div>
    </div>
</div>

<script>
function clearRouteCache() {
    if (confirm('Clear route cache?')) {
        // In production, implement AJAX call
        alert('Route cache clearing would be implemented here.');
    }
}

function clearViewCache() {
    if (confirm('Clear view cache?')) {
        // In production, implement AJAX call
        alert('View cache clearing would be implemented here.');
    }
}
</script>
@endsection