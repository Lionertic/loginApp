FROM php:7.3-fpm-alpine

MAINTAINER Lionertic <udayacharan.20cs@licet.ac.in>

COPY composer.lock composer.json /var/www/html/

WORKDIR /var/www/html

RUN apk --update add wget \
  curl \
  git \
  grep \
  build-base \
  libmemcached-dev \
  libmcrypt-dev \
  libxml2-dev \
  imagemagick-dev \
  pcre-dev \
  libtool \
  make \
  autoconf \
  g++ \
  cyrus-sasl-dev \
  libgsasl-dev \
  supervisor \
  git \
  nodejs \
  nodejs-npm

RUN docker-php-ext-install mysqli mbstring pdo pdo_mysql tokenizer xml

RUN pecl channel-update pecl.php.net \
    && pecl install memcached \
    && pecl install imagick \
    && docker-php-ext-enable memcached \
    && docker-php-ext-enable imagick

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN rm -rf /var/cache/apk/*


COPY ./config_files/supervisor/ /etc/supervisor/conf.d/
COPY ./config_files/supervisord.conf /etc/

COPY ./config_files/crontab/crontab /etc/cron.d/artisan-scheduler

RUN crontab /etc/cron.d/artisan-scheduler
RUN chmod 0644 /etc/cron.d/

EXPOSE 9000

ENTRYPOINT ["/usr/bin/supervisord"]

