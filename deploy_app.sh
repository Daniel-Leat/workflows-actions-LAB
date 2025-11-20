#!/bin/bash
set -e

APP_NAME="${1:-lab5app}"
APP_PORT="${2:-80}"
APP_DIR="/var/www/$APP_NAME"

echo "========================================="
echo "Deploying $APP_NAME on port $APP_PORT..."
echo "========================================="

# Prepare directory
echo "Creating application directory: $APP_DIR"
sudo mkdir -p "$APP_DIR"

# Set proper permissions
echo "Setting permissions..."
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 755 "$APP_DIR"

# Generate Apache config automatically
echo "Configuring Apache virtual host..."
sudo bash -c "cat > /etc/apache2/sites-available/$APP_NAME.conf" <<EOF
<VirtualHost *:$APP_PORT>
    ServerName localhost
    DocumentRoot $APP_DIR

    <Directory $APP_DIR>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/${APP_NAME}_error.log
    CustomLog \${APACHE_LOG_DIR}/${APP_NAME}_access.log combined

    # PHP configuration
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>
</VirtualHost>
EOF

# Enable site and required Apache modules
echo "Enabling Apache site and modules..."
sudo a2ensite $APP_NAME.conf
sudo a2enmod rewrite
sudo a2enmod php

# Test Apache configuration
echo "Testing Apache configuration..."
sudo apache2ctl configtest || true

# Reload Apache
echo "Reloading Apache..."
sudo systemctl reload apache2

echo "========================================="
echo "Deployment completed for $APP_NAME!"
echo "Application directory: $APP_DIR"
echo "Access logs: /var/log/apache2/${APP_NAME}_access.log"
echo "Error logs: /var/log/apache2/${APP_NAME}_error.log"
echo "========================================="
