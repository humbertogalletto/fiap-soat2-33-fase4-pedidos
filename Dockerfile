FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y \
    git \
    unzip \
    vim \
    curl \
    wget \
    libcurl4-openssl-dev pkg-config libssl-dev

RUN a2enmod rewrite

RUN pecl install mongodb && docker-php-ext-enable mongodb

#RUN echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini
#RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini
#RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/docker-php-ext-sodium.ini

WORKDIR /var/www/html

COPY . .

ENTRYPOINT [ "docker-php-entrypoint" ]

CMD [ "apache2-foreground" ]

RUN curl --version
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install
RUN composer require mongodb/mongodb
RUN composer dumpautoload
