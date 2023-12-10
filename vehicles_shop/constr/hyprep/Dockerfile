FROM phpdockerio/php:8.1-fpm

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
	# build-essential \
	# php8.1-dev \
	php8.1-xdebug\
    php8.1-gd \
    php8.1-mysql \
    php8.1-pdo \
    php8.1-ldap \
    php8.1-dom \
    php8.1-xml \
    php8.1-simplexml \
    php8.1-xmlreader \
    php8.1-tokenizer \
    php8.1-fileinfo \
    php8.1-zip \
    php8.1-curl \
    php8.1-xmlwriter \
    php-pear \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY 20-xdebug.ini /etc/php/8.1/fpm/conf.d/20-xdebug.ini
# ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# RUN pecl install xdebug \
#     && docker-php-ext-enable xdebug

# RUN pecl install xdebug-3.1.2
# RUN docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN usermod -u 1000 www-data

WORKDIR /var/www

EXPOSE 9000
