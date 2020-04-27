# Dockerfile
FROM php:7.4.1-fpm

RUN \
    # Prepare Package Manager and repositories
    apt-get -qq update && \
    apt-get -qq -y install ssh bash apt-utils apt-transport-https software-properties-common curl && \
    apt-get -qq update  && \
    #
    # Install Packages
    apt-get -qq -y install --allow-unauthenticated git vim zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
#RUN composer require symfony/console

WORKDIR /var/www/html

COPY composer.json ./

RUN composer install
