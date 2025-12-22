# StoreKing Template Deployment Guide

> Complete step-by-step guide for deploying StoreKing e-commerce template to DigitalOcean VPS.

---

## 📋 Prerequisites

| Item | Details |
|------|---------|
| Template | StoreKing (Laravel + Vue.js) |
| VPS | DigitalOcean Droplet (Ubuntu 22.04+) |
| Domain | Registered and ready |
| GitHub | Repository created |

---

## Phase 1: Local Preparation

### 1. Create `.env.example` for production

```env
APP_NAME="YourStoreName"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_DATABASE=yourdb_name
DB_USERNAME=yourdb_user
DB_PASSWORD=YOUR_SECURE_PASSWORD

TIMEZONE=Africa/Cairo
CURRENCY=EGP
DEMO=true
```

### 2. Push to GitHub

```bash
cd your-project-folder
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/username/repo.git
git push -u origin main
```

---

## Phase 2: VPS Server Setup

### Access VPS Console

1. Go to DigitalOcean → Droplets → Your Droplet
2. Click **Access** → **Launch Droplet Console**

### Install Dependencies (copy one by one)

```bash
# 1. Update system
apt update && apt upgrade -y

# 2. Add PHP repository
add-apt-repository ppa:ondrej/php -y && apt update

# 3. Install PHP 8.2 + extensions
apt install -y php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath php8.2-http php8.2-imagick

# 4. Install MySQL & Nginx
apt install -y mysql-server nginx

# 5. Install Node.js 18
curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt install -y nodejs

# 6. Install Composer
curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
```

---

## Phase 3: Clone & Configure Project

```bash
# Clone from GitHub
cd /var/www
git clone https://github.com/username/repo.git sitename
cd sitename

# Set ownership
chown -R www-data:www-data /var/www/sitename

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Configure Laravel
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

---

## Phase 4: Database Setup

```bash
# Enter MySQL
mysql
```

```sql
CREATE DATABASE yourdb_name;
CREATE USER 'yourdb_user'@'localhost' IDENTIFIED BY 'YourSecurePassword123!';
GRANT ALL PRIVILEGES ON yourdb_name.* TO 'yourdb_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Update .env with database credentials

```bash
nano .env
```

Update these lines:

```
DB_DATABASE=yourdb_name
DB_USERNAME=yourdb_user
DB_PASSWORD=YourSecurePassword123!
```

---

## Phase 5: Nginx Configuration

```bash
nano /etc/nginx/sites-available/sitename
```

Paste this config:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/sitename/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:

```bash
ln -s /etc/nginx/sites-available/sitename /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx
```

---

## Phase 6: DNS Configuration

In DigitalOcean → Networking → Domains:

| Type | Hostname | Value |
|------|----------|-------|
| A | @ | Select your Droplet |
| A | www | Select your Droplet |

---

## Phase 7: SSL Certificate

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

Follow prompts:

- Enter email → `Y` → `N` → Let it configure

---

## Phase 8: Final Laravel Setup

```bash
cd /var/www/sitename

# Permissions
chmod -R 777 storage
chown -R www-data:www-data storage bootstrap/cache

# Clear caches
php artisan config:clear
php artisan cache:clear

# Run migrations & seeders
php artisan migrate:fresh --seed --force
```

---

## Phase 9: Fix API Key Middleware (Important!)

This template has API key verification that blocks the frontend.

```bash
nano app/Http/Middleware/ApiKeyMiddleware.php
```

Replace ALL content with:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
```

Then:

```bash
php artisan config:clear && php artisan cache:clear
```

---

## Phase 10: Access Your Site

- **Frontend**: <https://yourdomain.com>
- **Admin Panel**: <https://yourdomain.com/admin>

### Default Admin Login

| Field | Value |
|-------|-------|
| Email | <admin@example.com> |
| Password | 123456 |

---

## 🔄 Future Updates Workflow

### On your local machine

```bash
# Make changes
git add .
git commit -m "Description of changes"
git push
```

### On the VPS

```bash
cd /var/www/sitename
git pull
npm run build  # if frontend changes
php artisan config:clear
php artisan cache:clear
```

---

## 📋 Credentials Template

Save this for each deployment:

```
Domain: 
VPS IP: 
GitHub Repo: 

Database:
- Host: localhost
- Name: 
- User: 
- Password: 

Admin:
- Email: admin@example.com
- Password: 123456

SSL Expiry: (90 days from install)
```

---

## ⚠️ Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| 500 Error | `php artisan key:generate && php artisan config:cache` |
| Session not saving | `chmod -R 777 storage` |
| Invalid Api Key | Modify `ApiKeyMiddleware.php` as shown above |
| Products not showing | Check `DEMO=true` in `.env` and run seeders |
| SSL fails | Wait 5 mins for DNS propagation |

---

## 🔧 Advanced Troubleshooting Guide

### Problem 1: Products Cannot Be Added (422 Error)

**Symptoms:**

- Clicking save does nothing
- Network shows 422 Unprocessable Content
- JavaScript error: `Cannot read properties of undefined`

**Cause:** Storage link not created or inaccessible

**Solution:**

```bash
cd /var/www/sitename

# Remove old storage link
rm -rf public/storage

# Create new storage link
php artisan storage:link

# Fix permissions
chmod -R 775 storage
chmod -R 775 public/storage
chown -R www-data:www-data storage
chown -R www-data:www-data public/storage

# Clear cache
php artisan config:clear
php artisan cache:clear
```

---

### Problem 2: Git Pull Conflicts with Build Files

**Symptoms:**

```
error: Your local changes to the following files would be overwritten by merge:
        public/build/manifest.json
```

**Solution:**

```bash
# Option 1: Discard local build files
git checkout -- public/build/manifest.json
git pull origin main
npm run build

# Option 2: Stash and pull
git stash
git pull origin main
npm run build
```

---

### Problem 3: Admin Panel Logo Too Large

**Cause:** Logo CSS using `w-full` instead of fixed height

**Solution:** Edit these files:

**BackendNavbarComponent.vue:**

```vue
<!-- Change from -->
<img class="w-full" :src="setting.theme_logo" alt="logo">

<!-- To -->
<img class="h-8 sm:h-10 w-auto max-w-full object-contain" :src="setting.theme_logo" alt="logo">
```

**BackendMenuComponent.vue:**

```vue
<!-- Change from -->
<img :src="setting.theme_logo" alt="logo">

<!-- To -->
<img class="h-8 w-auto max-w-full object-contain" :src="setting.theme_logo" alt="logo">
```

Then rebuild: `npm run build`

---

### Problem 4: Payment Gateways - Configure PayMob

**Goal:** Configure PayMob payment gateway for Egypt

**PayMob Setup:**

1. Create account at [paymob.com](https://accept.paymob.com)
2. Get your credentials from PayMob Dashboard:
   - API Key
   - Integration ID
   - IFrame ID
   - HMAC Secret
3. Enter these in Admin → Settings → Payment Gateway → PayMob

---

### Problem 5: Cannot Delete Products/Categories (Foreign Key Error)

**Symptoms:**

```
SQLSTATE[42000]: Cannot truncate a table referenced in a foreign key constraint
```

**Solution:**

```bash
php artisan tinker

# Disable foreign key checks temporarily
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

# Delete what you need
\App\Models\ProductVariation::truncate();
\App\Models\Product::truncate();
\App\Models\ProductCategory::where('id', '>', 1)->delete();

# Re-enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

exit
```

---

### Problem 6: Seeder Duplicate Entry Error

**Symptoms:**

```
Integrity constraint violation: 1062 Duplicate entry 'Admin-sanctum' for key 'roles.roles_name_guard_name_unique'
```

**Solution:** Run fresh migration instead:

```bash
php artisan migrate:fresh --seed --force
php artisan storage:link  # IMPORTANT: Don't forget this!
```

---

### Problem 7: Debug Product Creation Issues

**Add logging to ProductController.php:**

```php
public function store(ProductRequest $request)
{
    try {
        \Log::info('Product Store Request:', $request->all());
        return new ProductAdminResource($this->productService->store($request));
    } catch (Exception $exception) {
        \Log::error('Product Store Error: ' . $exception->getMessage());
        return response(['status' => false, 'message' => $exception->getMessage()], 422);
    }
}
```

Then check logs:

```bash
tail -50 storage/logs/laravel.log
```

---

### Problem 8: Missing Base Data (Categories, Units, Barcodes)

**Check if data exists:**

```bash
php artisan tinker

\App\Models\ProductCategory::count()
\App\Models\Unit::count()
\App\Models\Barcode::count()

exit
```

**If any return 0, run seeders:**

```bash
php artisan db:seed --force
```

---

## 🛠️ Useful Debug Commands

```bash
# Clear all caches
php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear

# Watch logs in real-time
tail -f storage/logs/laravel.log

# Check disk space
df -h

# Check PHP version
php -v

# Restart services
systemctl restart php8.2-fpm
systemctl restart nginx

# Test nginx config
nginx -t

# Check Laravel environment
php artisan env
```

---

## 📦 Adding New Payment Gateways

1. Create gateway class in `app/Http/PaymentGateways/Gateways/`
2. Add logo to `public/images/seeder/payment-gateway/`
3. Add entry to `PaymentGatewayTableSeeder.php`
4. Run: `php artisan db:seed --class=PaymentGatewayTableSeeder`

---

## 🎨 Customization Guide

### Disable Demo Mode & Remove Demo Buttons

**Symptoms:** Demo buttons (Admin, Customer, Manager, etc.) appear on login page

**Solution:**

```bash
# Edit .env file
nano .env

# Change DEMO=true to:
DEMO=false

# Save and rebuild
npm run build
php artisan config:clear
php artisan cache:clear
```

---

### Remove Auth Page Image (Vegetables Image)

**Files to edit (ALL 6 auth pages):**

- `resources/js/components/frontend/auth/LoginComponent.vue`
- `resources/js/components/frontend/auth/SignupComponent.vue`
- `resources/js/components/frontend/auth/SignupVerifyComponent.vue`
- `resources/js/components/frontend/auth/ForgotPasswordComponent.vue`
- `resources/js/components/frontend/auth/ForgotPasswordVerifyComponent.vue`
- `resources/js/components/frontend/auth/ResetPasswordComponent.vue`

**Change from:**

```vue
<div class="w-full max-w-3xl mx-auto rounded-2xl flex overflow-hidden gap-y-6 bg-white shadow-card sm:mb-20">
    <img :src="APP_URL + '/images/required/auth.png'" alt="auth-image"
        class="w-full hidden sm:block sm:max-w-xs md:max-w-sm flex-shrink-0" />
```

**To:**

```vue
<div class="w-full max-w-lg mx-auto rounded-2xl overflow-hidden bg-white shadow-card sm:mb-20">
```

Then rebuild: `npm run build`

---

### Configure Email/SMTP (For OTP & Notifications)

**Problem:** Email verification OTP not being sent

**Solution 1: Disable Email Verification (Quick Fix)**

In Admin Panel → Settings → OTP → Set Email Verification to "No"

Or via command:

```bash
php artisan tinker
\App\Models\Setting::where('key', 'site_email_verification')->update(['value' => '5']);
exit
```

**Solution 2: Configure SMTP (For Real Emails)**

Edit `.env` file:

```bash
nano /var/www/sitename/.env
```

Add/Update these settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Important for Gmail:**

1. Enable 2-Step Verification in your Google Account
2. Go to <https://myaccount.google.com/apppasswords>
3. Generate an App Password (16 characters)
4. Use that password in `MAIL_PASSWORD` (no spaces)

After configuration:

```bash
php artisan config:clear
php artisan cache:clear
```

---

### Fix Admin Panel Logo Size

**Files to edit:**

- `resources/js/components/layouts/backend/BackendNavbarComponent.vue`
- `resources/js/components/layouts/backend/BackendMenuComponent.vue`

**Change image tag to:**

```vue
<img class="h-8 sm:h-10 w-auto max-w-full object-contain" :src="setting.theme_logo" alt="logo">
```

---

### Add PayMob Payment Gateway Translations

**For Arabic Admin Panel:**
Edit `resources/js/languages/ar.json` and add to "label" section:

```json
"paymob_api_key": "مفتاح API باي موب",
"paymob_integration_id": "معرّف التكامل باي موب",
"paymob_iframe_id": "معرّف الإطار باي موب",
"paymob_hmac": "مفتاح HMAC باي موب",
"paymob_mode": "وضع باي موب",
"paymob_status": "حالة باي موب"
```

**For English Admin Panel:**
Edit `resources/js/languages/en.json` and add to "label" section:

```json
"paymob_api_key": "PayMob API Key",
"paymob_integration_id": "PayMob Integration ID",
"paymob_iframe_id": "PayMob IFrame ID",
"paymob_hmac": "PayMob HMAC Key",
"paymob_mode": "PayMob Mode",
"paymob_status": "PayMob Status"
```

---

### Clean Demo Data & Keep Essentials

```bash
php artisan tinker

# Disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

# Delete products
\App\Models\ProductVariation::truncate();
\App\Models\Product::truncate();

# Keep one category, delete rest
\App\Models\ProductCategory::where('id', '>', 1)->delete();

# Delete brands
\App\Models\ProductBrand::truncate();

# Delete orders
\App\Models\OrderItem::truncate();
\App\Models\OrderCoupon::truncate();
\App\Models\Order::truncate();

# Delete customers (keep admin)
\App\Models\User::where('id', '>', 1)->delete();

# Delete promotions & coupons
\App\Models\Promotion::truncate();
\App\Models\Coupon::truncate();

# Delete sliders
\App\Models\Slider::truncate();
\App\Models\ProductSection::truncate();

# Re-enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

exit
```

---

### Delete Demo Media/Images

```bash
# Delete all demo images from storage
rm -rf storage/app/public/*

# Delete media library entries
php artisan tinker
\Spatie\MediaLibrary\MediaCollections\Models\Media::truncate();
exit

# Clear cache
php artisan cache:clear
```

---

### Current Payment Gateways

**Available gateways:**

- Cash on Delivery (default)
- Credit (wallet balance)
- PayMob (Egypt - Cards, Wallets)

**To reset PayMob settings:**

```bash
php artisan db:seed --class=PaymentGatewayTableSeeder --force
php artisan config:clear
```

---

---

## 🔄 After Changes Workflow

After making any Vue.js/Frontend changes:

```bash
npm run build
git add .
git commit -m "Description of changes"
git push origin main
```

On the VPS:

```bash
cd /var/www/sitename
git pull origin main
npm run build  # Only if frontend changes
php artisan config:clear
php artisan cache:clear
systemctl restart php8.2-fpm
```

---

## ✅ Deployment Checklist

- [ ] GitHub repo created & code pushed
- [ ] VPS created with Ubuntu 22.04
- [ ] PHP 8.2, MySQL, Nginx, Node.js installed
- [ ] Project cloned to `/var/www/sitename`
- [ ] Composer & npm dependencies installed
- [ ] Database created and .env configured
- [ ] Nginx configured and enabled
- [ ] DNS A records pointing to VPS IP
- [ ] SSL certificate installed
- [ ] ApiKeyMiddleware bypassed
- [ ] Migrations & seeders run
- [ ] **Storage link created** (`php artisan storage:link`)
- [ ] Permissions set correctly
- [ ] DEMO=false in .env
- [ ] Site accessible via HTTPS
- [ ] Admin panel working
- [ ] Products can be added
- [ ] Payment gateways configured
- [ ] Auth page image removed (optional)
- [ ] Demo data cleaned (optional)
