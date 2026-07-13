# 🚀 Lozybyte — Live Server Deployment Guide
### Laravel (Backend API) + Next.js (Frontend) | VPS & cPanel

---

> **Architecture Reminder:**
> - **Backend (Laravel)** → runs at `api.lozybyte.com` or `lozybyte.com/api` — serves REST API & Admin panel
> - **Frontend (Next.js)** → runs at `lozybyte.com` — serves the public website
> - Both communicate via HTTP/JSON. Frontend fetches all content from the Laravel API.

---

## 📋 Table of Contents

1. [Server Requirements](#1-server-requirements)
2. [VPS Deployment (Ubuntu + Nginx) — Recommended](#2-vps-deployment-ubuntu--nginx)
3. [cPanel Deployment](#3-cpanel-deployment)
4. [Environment Variables Reference](#4-environment-variables-reference)
5. [Post-Deployment Checklist](#5-post-deployment-checklist)
6. [Updating the Live Site](#6-updating-the-live-site)
7. [Troubleshooting](#7-troubleshooting)

---

## 1. Server Requirements

| Component | Minimum Version |
|---|---|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| npm | 9+ |
| MySQL | 8.0+ (or MariaDB 10.4+) |
| Nginx | 1.20+ (VPS) |
| SSL Certificate | Let's Encrypt (free) or paid |
| RAM | 1 GB minimum, **2 GB recommended** |
| Storage | 5 GB+ |

> **Redis is optional** — the project works with database cache/sessions by default.

---

## 2. VPS Deployment (Ubuntu + Nginx) — ✅ Recommended

> Use this method if you have a VPS (DigitalOcean, Vultr, Linode, Contabo, etc.)

---

### Step 1 — Initial Server Setup

```bash
# Connect to your VPS
ssh root@YOUR_SERVER_IP

# Update system packages
apt update && apt upgrade -y

# Create a non-root user (replace "deploy" with your username)
adduser deploy
usermod -aG sudo deploy

# Switch to deploy user for remaining steps
su - deploy
```

---

### Step 2 — Install Required Software

```bash
# Install Nginx
sudo apt install nginx -y

# Install PHP 8.2 + required extensions
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
  php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd \
  php8.2-intl php8.2-redis php8.2-imagick

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Install Node.js 18.x (via NodeSource)
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install PM2 (keeps Next.js running in background)
sudo npm install -g pm2

# Verify installs
php -v         # Should show PHP 8.2.x
composer -V    # Should show Composer 2.x
node -v        # Should show v18.x.x
pm2 -v         # Should show PM2 version
```

---

### Step 3 — Set Up MySQL Database

```bash
# Login to MySQL
sudo mysql -u root -p

# Run these SQL commands:
CREATE DATABASE lozybyte_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'lozybyte_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON lozybyte_db.* TO 'lozybyte_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

### Step 4 — Clone the Repository

```bash
# Navigate to the web directory
cd /var/www

# Clone your project (replace with your actual repo URL)
sudo git clone https://github.com/YOUR_USERNAME/lozybyte.git
sudo chown -R deploy:www-data /var/www/lozybyte
cd /var/www/lozybyte
```

---

### Step 5 — Deploy the Laravel Backend

```bash
# Install PHP dependencies (production mode - no dev packages)
composer install --optimize-autoloader --no-dev

# Copy the environment file
cp .env.example .env

# Open and configure .env (see Section 4 for all values)
nano .env
```

**Configure your `.env` with production values:**

```env
APP_NAME="Lozybyte"
APP_ENV=production
APP_KEY=              # Will be generated in next step
APP_DEBUG=false
APP_URL=https://api.lozybyte.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lozybyte_db
DB_USERNAME=lozybyte_user
DB_PASSWORD=YOUR_STRONG_PASSWORD

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true

CORS_ALLOWED_ORIGINS=https://lozybyte.com,https://www.lozybyte.com
FRONTEND_URL=https://lozybyte.com

CACHE_STORE=database
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_LEVEL=error

FILESYSTEM_DISK=public
```

```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Create storage symlink (for uploaded files)
php artisan storage:link

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set correct file permissions
sudo chown -R www-data:www-data /var/www/lozybyte/storage
sudo chown -R www-data:www-data /var/www/lozybyte/bootstrap/cache
sudo chmod -R 775 /var/www/lozybyte/storage
sudo chmod -R 775 /var/www/lozybyte/bootstrap/cache
```

---

### Step 6 — Deploy the Next.js Frontend

```bash
# Navigate to frontend directory
cd /var/www/lozybyte/frontend

# Create the frontend environment file
nano .env.local
```

**Frontend `.env.local` (production):**

```env
NEXT_PUBLIC_API_URL=https://api.lozybyte.com/api
NEXT_PUBLIC_SITE_URL=https://lozybyte.com
NEXT_PUBLIC_FRONTEND_URL=https://lozybyte.com
NODE_ENV=production
```

```bash
# Install dependencies
npm install

# Build the production bundle
npm run build

# Start with PM2 (auto-restart on crash, survives reboots)
pm2 start npm --name "lozybyte-frontend" -- start

# Save PM2 process list so it restarts after server reboot
pm2 save
pm2 startup  # Follow the printed instructions
```

**Verify it's running:**
```bash
pm2 status        # Should show "lozybyte-frontend" as "online"
pm2 logs lozybyte-frontend   # View live logs
```

> **Next.js runs on port 3000** by default. Nginx will proxy traffic to it.

---

### Step 7 — Configure Nginx Virtual Hosts

#### 7a. Backend (Laravel API) — `api.lozybyte.com`

```bash
sudo nano /etc/nginx/sites-available/lozybyte-api
```

```nginx
server {
    listen 80;
    server_name api.lozybyte.com;
    root /var/www/lozybyte/public;
    index index.php;

    # Max upload size (for media uploads)
    client_max_body_size 50M;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 60;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|webp|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri /index.php?$query_string;
    }
}
```

#### 7b. Frontend (Next.js) — `lozybyte.com`

```bash
sudo nano /etc/nginx/sites-available/lozybyte-frontend
```

```nginx
server {
    listen 80;
    server_name lozybyte.com www.lozybyte.com;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Proxy all traffic to Next.js (port 3000)
    location / {
        proxy_pass http://127.0.0.1:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        proxy_read_timeout 60s;
    }

    # Serve Next.js static files directly (performance boost)
    location /_next/static/ {
        alias /var/www/lozybyte/frontend/.next/static/;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Serve public assets directly
    location /images/ {
        alias /var/www/lozybyte/frontend/public/images/;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# Enable both sites
sudo ln -s /etc/nginx/sites-available/lozybyte-api /etc/nginx/sites-enabled/
sudo ln -s /etc/nginx/sites-available/lozybyte-frontend /etc/nginx/sites-enabled/

# Test Nginx config
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

### Step 8 — SSL Certificates (HTTPS) — Free via Let's Encrypt

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Get SSL for both domains at once
sudo certbot --nginx -d lozybyte.com -d www.lozybyte.com -d api.lozybyte.com

# Follow the prompts (enter email, agree to ToS, redirect HTTP to HTTPS)

# Auto-renewal is set up automatically. Test it:
sudo certbot renew --dry-run
```

> ✅ After this, your site will run on **HTTPS** and all HTTP will redirect automatically.

---

### Step 9 — Set Up Queue Worker (for background jobs)

```bash
# Create a supervisor config for queue worker
sudo apt install supervisor -y
sudo nano /etc/supervisor/conf.d/lozybyte-worker.conf
```

```ini
[program:lozybyte-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/lozybyte/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/lozybyte/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start lozybyte-worker:*
```

---

## 3. cPanel Deployment

> Use this if your host only gives you cPanel (shared hosting or VPS with cPanel).

> ⚠️ **Important:** cPanel shared hosting may not support running Node.js as a persistent process. You need a **VPS with cPanel** or a host that offers **"Setup Node.js App"** feature (e.g. Namecheap VPS, Hostinger VPS, SiteGround Cloud).

---

### Step 1 — Upload Laravel Backend Files

1. In **cPanel → File Manager**, navigate to `public_html/` (or create a subdirectory like `lozybyte-api/`)
2. Upload all Laravel files **except** `public/` contents
3. Move the contents of `public/` into `public_html/` (this is your document root)
4. Edit `public_html/index.php` — update the path to point to your Laravel root:

```php
// Change this line:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
// Make sure paths point to where your laravel files are
```

> **Better approach:** Place all Laravel files in `~/lozybyte/` and only put the contents of `public/` in `public_html/`. Then update `public/index.php` accordingly.

---

### Step 2 — Create MySQL Database via cPanel

1. Go to **cPanel → MySQL Databases**
2. Create a new database: `yourusername_lozybyte`
3. Create a new user: `yourusername_lozybyte_user` with a strong password
4. Add user to database with **All Privileges**
5. Note down: database name, username, password

---

### Step 3 — Configure Laravel `.env`

1. In **File Manager**, navigate to your Laravel root (e.g. `~/lozybyte/`)
2. Create `.env` file (copy from `.env.example`)
3. Edit with your cPanel values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=yourusername_lozybyte
DB_USERNAME=yourusername_lozybyte_user
DB_PASSWORD=YOUR_PASSWORD

CORS_ALLOWED_ORIGINS=https://yourdomain.com,https://www.yourdomain.com
FRONTEND_URL=https://yourdomain.com
```

---

### Step 4 — Run Artisan Commands via cPanel Terminal

1. **cPanel → Terminal** (or SSH into your account)

```bash
cd ~/lozybyte   # Your Laravel root
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### Step 5 — Deploy Next.js on cPanel

1. Go to **cPanel → Setup Node.js App**
2. Click **"Create Application"**
3. Set:
   - **Node.js version:** 18.x
   - **Application mode:** Production
   - **Application root:** `frontend/` (path to your Next.js folder)
   - **Application URL:** Your domain or subdomain (e.g. `lozybyte.com`)
   - **Application startup file:** `node_modules/next/dist/bin/next`
4. Click **"Create"**

5. In the cPanel Terminal for the app, run:

```bash
cd ~/frontend
npm install
npm run build
```

6. Go back to **Setup Node.js App** and click **"Restart"**

---

### Step 6 — Set Up `.htaccess` for Laravel (cPanel/Apache)

Make sure `public_html/.htaccess` looks like this:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## 4. Environment Variables Reference

### Laravel Backend `.env`

```env
# ══════════════════════════════════════════
# APP CORE
# ══════════════════════════════════════════
APP_NAME="Lozybyte"
APP_ENV=production           # ← MUST be "production" on live server
APP_KEY=                     # ← Generated by: php artisan key:generate
APP_DEBUG=false              # ← MUST be false in production (security!)
APP_URL=https://api.lozybyte.com   # ← Your backend domain

# ══════════════════════════════════════════
# DATABASE
# ══════════════════════════════════════════
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lozybyte_db
DB_USERNAME=lozybyte_user
DB_PASSWORD=VERY_STRONG_PASSWORD_HERE

# ══════════════════════════════════════════
# SESSION & SECURITY
# ══════════════════════════════════════════
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true   # ← Requires HTTPS
SESSION_SAME_SITE=lax

# ══════════════════════════════════════════
# CORS — Frontend domains allowed to call the API
# ══════════════════════════════════════════
CORS_ALLOWED_ORIGINS=https://lozybyte.com,https://www.lozybyte.com
FRONTEND_URL=https://lozybyte.com

# ══════════════════════════════════════════
# CACHE & QUEUE
# ══════════════════════════════════════════
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

# ══════════════════════════════════════════
# LOGGING (errors only in production)
# ══════════════════════════════════════════
LOG_CHANNEL=stack
LOG_LEVEL=error

# ══════════════════════════════════════════
# MAIL (configure for contact form emails)
# ══════════════════════════════════════════
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com       # Or your mail provider
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=info@lozybyte.com
MAIL_FROM_NAME="Lozybyte"

# ══════════════════════════════════════════
# RATE LIMITS
# ══════════════════════════════════════════
API_RATE_LIMIT=120
CONTACT_RATE_LIMIT=5
LOGIN_RATE_LIMIT=5
```

---

### Frontend `frontend/.env.local` (Next.js)

```env
# ══════════════════════════════════════════
# API — Must point to the Laravel backend
# ══════════════════════════════════════════
NEXT_PUBLIC_API_URL=https://api.lozybyte.com/api

# ══════════════════════════════════════════
# Site URL — Your public frontend domain
# ══════════════════════════════════════════
NEXT_PUBLIC_SITE_URL=https://lozybyte.com
NEXT_PUBLIC_FRONTEND_URL=https://lozybyte.com

# ══════════════════════════════════════════
# Optional: Google Analytics / Search Console
# ══════════════════════════════════════════
NEXT_PUBLIC_GA_ID=G-XXXXXXXXXX
NEXT_PUBLIC_GOOGLE_SITE_VERIFICATION=your_verification_code
```

---

## 5. Post-Deployment Checklist

Run through this after every fresh deployment:

### ✅ Backend (Laravel) Checks

```bash
# Verify app key is set
php artisan key:generate --show

# Check all migrations ran
php artisan migrate:status

# Ensure storage symlink exists
ls -la public/storage   # Should show symlink → ../storage/app/public

# Optimize caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear old caches if redeploying
php artisan cache:clear
php artisan view:clear
```

**Manual checks:**
- [ ] Visit `https://api.lozybyte.com/api/settings` — should return JSON data
- [ ] Visit `https://api.lozybyte.com/api/homepage` — should return homepage sections
- [ ] Visit `https://api.lozybyte.com/admin/login` — Admin login page should load
- [ ] Admin login works with your credentials
- [ ] Image uploads work (test from admin panel)
- [ ] `APP_DEBUG=false` is confirmed in `.env`
- [ ] `APP_ENV=production` is confirmed in `.env`

### ✅ Frontend (Next.js) Checks

```bash
pm2 status              # lozybyte-frontend should be "online"
pm2 logs lozybyte-frontend --lines 50   # No errors in logs
```

**Manual checks:**
- [ ] Visit `https://lozybyte.com` — homepage loads with correct content
- [ ] All CMS content (services, team, pricing, etc.) displays correctly
- [ ] Images load from the API backend
- [ ] Cookie consent banner appears
- [ ] Contact form submits successfully
- [ ] Blog pages work (`/blog`, `/blog/[slug]`)
- [ ] Portfolio pages work (`/portfolio`, `/portfolio/[slug]`)
- [ ] Privacy/Terms/Refund pages load correctly

### ✅ Security Checks

- [ ] HTTPS is enforced on all pages (HTTP redirects to HTTPS)
- [ ] Admin panel only accessible via `api.lozybyte.com/admin`
- [ ] `APP_DEBUG=false` confirmed
- [ ] Database credentials are strong
- [ ] No `.env` file is publicly accessible (test: visit `https://api.lozybyte.com/.env` — should return 404)

---

## 6. Updating the Live Site

> When you push new code changes, follow these steps:

### Backend Update

```bash
cd /var/www/lozybyte

# Pull latest code
git pull origin main

# Install any new PHP dependencies
composer install --optimize-autoloader --no-dev

# Run new migrations (if any)
php artisan migrate --force

# Clear and rebuild all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reload PHP-FPM (no downtime)
sudo systemctl reload php8.2-fpm
```

### Frontend Update

```bash
cd /var/www/lozybyte/frontend

# Pull latest code (already done above with git pull)

# Install any new JS dependencies
npm install

# Build the new production bundle
npm run build

# Restart Next.js with zero-downtime reload
pm2 reload lozybyte-frontend
```

---

## 7. Troubleshooting

### ❌ 500 Internal Server Error on Laravel

```bash
# Check the logs
tail -n 100 /var/www/lozybyte/storage/logs/laravel.log

# Fix permissions
sudo chown -R www-data:www-data /var/www/lozybyte/storage
sudo chmod -R 775 /var/www/lozybyte/storage
sudo chmod -R 775 /var/www/lozybyte/bootstrap/cache
```

### ❌ CORS Error on Frontend

```bash
# Check your backend .env
cat /var/www/lozybyte/.env | grep CORS
# Should be: CORS_ALLOWED_ORIGINS=https://lozybyte.com,https://www.lozybyte.com

# Clear config cache after updating .env
php artisan config:clear
php artisan config:cache
```

### ❌ Next.js shows "Loading..." but no content appears

```bash
# Check frontend logs
pm2 logs lozybyte-frontend

# Verify the API URL is correct in .env.local
cat /var/www/lozybyte/frontend/.env.local

# Test the API directly
curl https://api.lozybyte.com/api/settings
```

### ❌ Images not loading (broken images from API)

```bash
# Recreate the storage symlink
cd /var/www/lozybyte
php artisan storage:link

# Check if symlink exists
ls -la public/storage

# Verify file permissions
sudo chown -R www-data:www-data storage/app/public/
```

### ❌ PM2 not starting after server reboot

```bash
# Regenerate startup script
pm2 startup
# Copy and run the command it prints

pm2 save
```

### ❌ "Target class [request] does not exist" in artisan

This is a **known Laravel 11 behavior** — certain artisan commands can't run without an HTTP context due to middleware binding. It does **not affect** the running website. To clear caches safely:

```bash
php artisan config:clear --no-interaction 2>/dev/null || true
php artisan view:clear --no-interaction 2>/dev/null || true
```

### ❌ Nginx 502 Bad Gateway

```bash
# Check if PHP-FPM is running
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm

# Check if Next.js is running
pm2 status
pm2 restart lozybyte-frontend

# Check Nginx error logs
sudo tail -n 50 /var/log/nginx/error.log
```

---

## 📌 Quick Reference — Important Paths

| Item | Path |
|---|---|
| Laravel root | `/var/www/lozybyte/` |
| Laravel `.env` | `/var/www/lozybyte/.env` |
| Laravel logs | `/var/www/lozybyte/storage/logs/laravel.log` |
| Uploaded media | `/var/www/lozybyte/storage/app/public/` |
| Next.js root | `/var/www/lozybyte/frontend/` |
| Next.js `.env` | `/var/www/lozybyte/frontend/.env.local` |
| Nginx config (API) | `/etc/nginx/sites-available/lozybyte-api` |
| Nginx config (Frontend) | `/etc/nginx/sites-available/lozybyte-frontend` |
| PM2 logs | `pm2 logs lozybyte-frontend` |

---

## 📌 Quick Commands Cheat Sheet

```bash
# Restart Laravel (PHP-FPM)
sudo systemctl reload php8.2-fpm

# Restart Next.js
pm2 reload lozybyte-frontend

# View Next.js live logs
pm2 logs lozybyte-frontend

# Clear all Laravel caches
php artisan optimize:clear

# Re-optimize Laravel for production
php artisan optimize

# Check Nginx config
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx

# Renew SSL certificates
sudo certbot renew
```

---

*Guide generated for Lozybyte headless stack — Laravel 11 + Next.js 14 — July 2026*
