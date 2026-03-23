FROM dunglas/frankenphp:latest

RUN apt update && \
    apt upgrade -y && \
    apt install -y nodejs \
    npm \
    htop \
    unzip 

# Cài đặt các PHP extensions phổ biến
RUN install-php-extensions \
    pdo_mysql \
    gd \
    intl \
    zip \
    opcache \
    redis \
    pcntl 

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# cài laravel 
RUN composer global require laravel/installer

# Set working directory
WORKDIR /app

# Copy application code
COPY . /app

# Set permissions
RUN chown -R www-data:www-data /app

# Expose port
EXPOSE 80 443 5173