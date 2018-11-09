FROM php:7.2-fpm

# Install any custom system requirements here
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    curl \
    libicu-dev \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng12-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    libxml2-dev \
    libbz2-dev \
    libjpeg62-turbo-dev \
    php-pear \
    curl \
    git \
  && rm -rf /var/lib/apt/lists/*

# Install various PHP extensions
RUN docker-php-ext-configure bcmath --enable-bcmath \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-configure mbstring --enable-mbstring \
    && docker-php-ext-configure soap --enable-soap \
    && docker-php-ext-install \
        bcmath \
        intl \
        mbstring \
        mcrypt \
        pcntl \
        soap \
        sockets \
        zip \
  && docker-php-ext-configure gd \
    --enable-gd-native-ttf \
    --with-jpeg-dir=/usr/lib \
    --with-freetype-dir=/usr/include/freetype2 && \
    docker-php-ext-install gd \


# Copy timezone configration
COPY ./timezone.ini /usr/local/etc/php/conf.d/timezone.ini

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/London /etc/localtime
RUN "date"

# Short open tags fix - another Symfony requirements
COPY ./custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini

# Composer
ENV COMPOSER_HOME /home/workdirs/test_symfony/.composer

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin \
    --filename=composer

VOLUME $COMPOSER_HOME