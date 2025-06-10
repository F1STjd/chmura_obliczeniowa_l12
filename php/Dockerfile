# PHP-FPM Dockerfile with MySQL Extensions
# Based on Alpine Linux for smaller image size

FROM php:8.2-fpm-alpine

# Set maintainer
LABEL maintainer="Konrad Nowak <konrad@example.com>"
LABEL description="PHP-FPM with MySQL/PDO extensions for LEMP stack"

# Install system dependencies first
RUN apk add --no-cache \
        # System packages
        curl \
        git \
        zip \
        unzip \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        icu-dev \
        oniguruma-dev \
        # Build dependencies
        $PHPIZE_DEPS

# Configure and install PHP extensions
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg

# Install MySQL and PDO extensions first
RUN docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mysqli

# Install other extensions
RUN docker-php-ext-install -j$(nproc) \
        zip \
        gd \
        intl \
        mbstring \
        opcache \
        bcmath \
        exif

# Clean up
RUN apk del $PHPIZE_DEPS \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy custom PHP configuration
COPY php.ini /usr/local/etc/php/php.ini

# Create PHP-FPM user
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -D -S -G www www

# Change ownership of working directory
RUN chown -R www:www /var/www/html

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD php-fpm-healthcheck || exit 1

# Use the default production configuration
CMD ["php-fpm"]
