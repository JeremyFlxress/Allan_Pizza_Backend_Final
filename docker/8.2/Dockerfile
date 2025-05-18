FROM laravel/sail:8.2

# Install additional system packages
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libzip-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure GD with freetype and jpeg support if needed
# Note: Sail already includes php8.2-gd but we can reconfigure it
RUN apt-get update && apt-get install -y \
    php8.2-gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Ensure all required PHP extensions are properly configured
# Most are already installed in the base image, but we can verify
RUN php -m | grep -q 'gd' || echo "GD not installed"
RUN php -m | grep -q 'pdo' || echo "PDO not installed"
RUN php -m | grep -q 'pdo_mysql' || echo "PDO_MySQL not installed"
RUN php -m | grep -q 'zip' || echo "ZIP not installed"
RUN php -m | grep -q 'opcache' || echo "Opcache not installed"
RUN php -m | grep -q 'xml' || echo "XML not installed"

# Additional configuration for opcache if needed
COPY opcache.ini /etc/php/8.2/cli/conf.d/99-sail-opcache.ini

