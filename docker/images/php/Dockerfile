FROM php:7.3-cli
MAINTAINER rsouverain
LABEL php.version=7.3

WORKDIR /var/www/app

# # Install composer
# RUN cd /usr/bin \
#     && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
#     && php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
#     && php composer-setup.php \
#     && php -r "unlink('composer-setup.php');"

# Install packages
RUN apt-get update && apt-get install -y \
    curl \
    libyaml-dev \
    libmemcached-dev \
    zlib1g-dev \
    zip unzip \
    git

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Install PHP OPCache
RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

# Install PHP extensions
RUN pecl install -o -f yaml \
    && docker-php-ext-enable yaml

# @see https://github.com/phpredis/phpredis
# @see https://medium.com/assoconnect/how-to-use-persistent-connections-with-redis-for-symfony-cache-with-php-fpm-3e7bd1100736
RUN pecl install -o -f redis \
    && docker-php-ext-enable redis

RUN rm -rf /tmp/pear
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

COPY run.sh /run.sh
RUN chmod 755 /run.sh

WORKDIR /project

# Configure timezone
ARG DOCKER_TIMEZONE=UTC
RUN echo $DOCKER_TIMEZONE > /etc/timezone;

# Run app
#CMD [ "composer install && php -S 0.0.0.0:9632 -t /project/app/public /project/app/public/index.php" ]