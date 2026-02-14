# License Server

This is the central licensing server for your SaaS application. It manages license keys, domain validation, and activation control.

## Setup Instructions

1. **Upload to Server**:
   - Upload all files from `license-server-root/` folder to your server (e.g., `server.4amtech.in`)
   - Ensure the web server points to the `public/` directory

2. **Install Dependencies** (if needed):
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Web-Based Installation**:
   - Visit: `https://server.4amtech.in/install`
   - Fill in your database details:
     - Database Host
     - Database Name
     - Database Username
     - Database Password
     - Application Key (32 characters, e.g., generate with `openssl rand -base64 32`)

4. **Automatic Setup**:
   - The installer will:
     - Test database connection
     - Create `.env` file
     - Create `users` and `licenses` tables
     - Create admin user (email: `admin@license-server.com`, password: `password`)
     - Generate a unique license key for this installation
     - Mark installation as complete

5. **Post-Installation**:
   - After installation, visit the root URL to see the generated license key
   - Use this key in your main application for activation
   - Login to admin panel at `/login` with the admin credentials

6. **Configure Main App**:
   - The main app is already configured to use `https://server.4amtech.in/api/license`
   - If deployed elsewhere, update `config/system.php`:
   ```php
   'license' => [
       'server_url' => 'https://your-license-server.com/api/license',
   ],
   ```

## API Endpoints

### Public Endpoints (No Authentication Required)

#### Activate License (Client Side)
```http
POST /api/license/activate
Content-Type: application/json

{
    "license_key": "ROYAL-ABC123...",
    "domain": "clientdomain.com",
    "client_name": "Client Company",
    "email": "client@email.com"
}
```

#### Validate License (Periodic Check)
```http
POST /api/license/validate
Content-Type: application/json

{
    "license_key": "ROYAL-ABC123...",
    "domain": "clientdomain.com"
}
```

### Admin Endpoints (Require Authentication)

#### Generate License Key
```http
POST /api/license/generate
Authorization: Bearer {token}
Content-Type: application/json

{
    "type": "extended",
    "expires_at": "2026-12-31" // optional
}
```

#### List Licenses
```http
GET /api/license/list
Authorization: Bearer {token}
```

#### Suspend License
```http
POST /api/license/suspend/ROYAL-ABC123...
Authorization: Bearer {token}
```

## Admin Web Interface

- **Login**: `/login` (email: `admin@license-server.com`, password: `password`)
- **Dashboard**: `/admin` - Manage licenses, generate new keys, suspend/activate existing ones

## Usage

1. **Generate Keys**: Use the generate endpoint to create license keys
2. **Distribute**: Send keys to customers
3. **Customers Activate**: Customers enter keys in their admin panel
4. **Validation**: Server validates all activations and periodic checks

## Security Features

- Domain locking (one domain per key)
- Remote suspension capability
- Centralized key management
- No local key generation on client installations

## File Structure

```
license-server/
├── app/
│   ├── Http/Controllers/Api/
│   │   └── LicenseController.php
│   └── Models/
│       └── License.php
├── database/migrations/
│   └── 2025_12_17_000000_create_licenses_table.php
├── routes/
│   └── api.php
├── .env.example
├── composer.json
└── README.md