FROM php:8.3-fpm

ARG PUID=1000
ARG PGID=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    libpq-dev \
    libmemcached-dev \
    libredis-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql pdo_sqlite zip

RUN pecl install redis && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g ${PGID} appgroup && useradd -u ${PUID} -g appgroup -s /bin/bash appuser

WORKDIR /var/www

COPY --chown=appuser:appgroup . .

USER appuser

RUN composer install --optimize-autoloader --no-dev

RUN npm ci --production

RUN npm run build

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
