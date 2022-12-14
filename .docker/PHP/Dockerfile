FROM php:8.1-fpm
RUN apt-get update && apt-get install -y \
  cron \
  git \
  libfreetype6-dev \
  libicu-dev \
  libjpeg62-turbo-dev \
  libmagickwand-dev \
  libmcrypt-dev \
  libpng-dev \
  libxslt1-dev \
  default-mysql-client \
  vim \
  zip \
  libzip-dev \
  wget \
  net-tools \
  netcat

RUN docker-php-ext-configure \
  gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/

RUN docker-php-ext-install \
  bcmath \
  gd \
  gettext \
  intl \
  opcache \
  pdo_mysql \
  soap \
  xsl \
  zip \
  sockets

RUN pecl channel-update pecl.php.net \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && sed -i -e 's/^zend_extension/\;zend_extension/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN pecl install imagick \
  && docker-php-ext-enable imagick

RUN curl -sS https://getcomposer.org/installer | \
  php -- --install-dir=/usr/local/bin --filename=composer


RUN wget https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x mhsendmail_linux_amd64 \
    &&  mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail

COPY conf/php.ini /usr/local/etc/php/

WORKDIR /app

EXPOSE 9001