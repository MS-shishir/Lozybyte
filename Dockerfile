FROM richarvey/nginx-php-fpm:3.1.6

# Copy application source files
COPY . /var/www/html

# Nginx-PHP-FPM base image configuration
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel environment defaults (can be overridden by Render dashboard env variables)
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow Composer to execute commands as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Run Composer installation during build phase (cached in docker image layer)
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Setup proper directory permissions for Laravel storage & bootstrap cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Start the web server and PHP-FPM
CMD ["/start.sh"]
