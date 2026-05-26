FROM php:8.3-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    zip \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    tokenizer \
    xml \
    ctype \
    bcmath \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

EXPOSE 10000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]