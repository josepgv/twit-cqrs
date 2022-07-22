FROM php:8.1.3-fpm

ARG UID

# Create user with same permissions as host and some useful stuff
RUN adduser -u ${UID} --disabled-password --gecos "" appuser
RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser/
RUN echo "StrictHostKeyChecking no" >> /home/appuser/.ssh/config
RUN echo "alias sf=/var/www/html/app/bin/console" >> /home/appuser/.bashrc


RUN apt-get update && apt-get install -y wget git zip libcurl4-openssl-dev pkg-config libssl-dev libicu-dev

RUN apt-get install -y librabbitmq-dev libssh-dev \
    && docker-php-ext-install opcache bcmath sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp

RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN pecl install redis && docker-php-ext-enable redis

RUN docker-php-ext-install pdo pdo_mysql pcntl intl

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN composer self-update

## Install Symfony binary
USER appuser
RUN wget https://get.symfony.com/cli/installer -O - | bash
USER root
RUN mv /home/appuser/.symfony/bin/symfony /usr/local/bin/symfony
RUN mkdir /opt/phpstorm-coverage
RUN chown -R appuser:appuser /opt/phpstorm-coverage
RUN chmod -R 777 /opt/phpstorm-coverage

ARG WITH_XDEBUG=true

RUN if [ $WITH_XDEBUG = true ] ; then \
            pecl install xdebug; \
            docker-php-ext-enable xdebug; \
            echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
            echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
            echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
            echo "log_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
            echo "memory_limit = -1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
            echo "max_execution_time = 3600" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
            echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
            echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
            echo "xdebug.max_nesting_level=512" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        fi ;
