# Valdor E-Commerce Deployment Guide

## Server Requirements

- Ubuntu 22.04 LTS
- PHP 8.2+ with: curl, mbstring, xml, zip, gd, mysql, http, intl, bcmath
- MySQL 8.0+
- Node.js 18+
- Nginx
- Composer

## Quick Deployment

### 1. Server Setup

```bash
# Update & Install PHP
sudo apt update && sudo apt upgrade -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt install -y php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-http php8.2-intl php8.2-bcmath

# Install MySQL, Nginx, Node.js
sudo apt install -y mysql-server nginx
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Clone & Configure

```bash
cd /var/www
sudo git clone https://github.com/johnrecap/valdor.git valdor
cd valdor
sudo chown -R www-data:www-data .

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Configure Laravel
cp .env.example .env
nano .env  # Edit database credentials
php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Database

```bash
# Create database & user
sudo mysql
CREATE DATABASE valdor_db;
CREATE USER 'valdor_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON valdor_db.* TO 'valdor_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import your database
mysql -u valdor_user -p valdor_db < your_database.sql
```

### 4. Nginx Config

Create `/etc/nginx/sites-available/valdor`:

```nginx
server {
    listen 80;
    server_name valdor.me www.valdor.me;
    root /var/www/valdor/public;
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

Enable:

```bash
sudo ln -s /etc/nginx/sites-available/valdor /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### 5. SSL Certificate

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d valdor.me -d www.valdor.me
```

### 6. Permissions

```bash
sudo chown -R www-data:www-data /var/www/valdor
sudo chmod -R 775 storage bootstrap/cache
```

## Post-Deployment

- Access: <https://valdor.me>
- Admin: <https://valdor.me/admin>
