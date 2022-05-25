FROM php:7.4.28-apache

RUN pecl install \
    && pecl install xdebug-2.8.1 \
    && docker-php-ext-enable xdebug

RUN apt-get update
RUN apt-get install -y libmcrypt-dev
RUN pecl install mcrypt-1.0.4
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable mcrypt
RUN echo 'zend_extension="/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so"' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini
RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/php.ini
RUN echo 'extension=mcrypt.so' >> /usr/local/etc/php/php.ini