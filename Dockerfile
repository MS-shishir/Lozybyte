FROM serversideup/php:8.3-fpm-nginx

# Switch to root user to copy files and adjust permissions
USER root

# Copy application source files and set owner to www-data
COPY --chown=www-data:www-data . /var/www/html

# Switch back to unprivileged www-data user for execution
USER www-data

# Run Composer installation during build phase
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

