# License System Setup Guide

This project includes a secure, self-hosted license verification system consisting of two parts:
1. **Main Application**: The SaaS product to be licensed.
2. **License Server**: A standalone Laravel application that issues and validates licenses.

## 1. System Requirements
- PHP 8.1+
- MySQL
- Composer

## 2. Installation

### A. License Server
The License Server must be hosted on a publicly accessible domain (or localhost for testing).

1. Navigate to `license-server-root`.
2. Configure `.env`:
   ```env
   DB_DATABASE=license_server
   DB_USERNAME=root
   DB_PASSWORD=
   APP_URL=https://your-license-server.com
   ```
3. Run Setup:
   ```bash
   composer install
   php artisan migrate --seed
   php artisan key:generate
   ```
4. **Admin Access**:
   Access `/login`. Default credentials (seed): `admin@license-server.com` / `password` (check UserSeeder).
   Generate keys at `/admin`.

### B. Main Application
1. Configure `.env` to point to the license server:
   ```env
   SYSTEM_LICENSE_SERVER_URL=https://your-license-server.com/api
   SYSTEM_LICENSE_REQUIRE_ONLINE=true
   ```
2. Run Migrations (for local license cache table):
   ```bash
   php artisan migrate
   ```

## 3. API Endpoints (License Server)
- **POST** `/api/license/activate`
  - Params: `license_key`, `client_name`, `email`, `domain`
  - Returns: `{ "valid": true, "type": "extended", "expires_at": "..." }`
- **POST** `/api/license/validate`
  - Params: `license_key`, `domain`
  - Returns: `{ "valid": true/false }`

## 4. Testing Locally
To test the integration on a single machine:
1. Start License Server: `php artisan serve --port=8001`
2. Start Main App: `php artisan serve --port=8000`
3. Generate a key in License Server Admin (Port 8001).
4. Activate it in Main App Admin (Port 8000).

## 5. Security Features
- **Domain Locking**: Licenses are locked to the domain where they are first activated.
- **Server-Side Validation**: The main app periodically checks the license status with the server.
- **Strict Parsing**: URL construction middleware ensures consistent API communication.
