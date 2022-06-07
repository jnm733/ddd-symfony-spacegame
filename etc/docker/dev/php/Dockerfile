FROM php:7.4.27-fpm-alpine

WORKDIR /app

RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make bash gcc g++ icu-dev zsh git supervisor rabbitmq-c rabbitmq-c-dev \
    && pecl install apcu-5.1.18 \
    && pecl install amqp-1.9.4 \
    && docker-php-ext-install -j$(nproc) \
        opcache \
        intl \
        pdo_mysql \
        bcmath \
        sockets \
    && docker-php-ext-enable \
        amqp \
        apcu \
        opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk --update upgrade \
    && apk add --no-cache \
       autoconf \
       automake \
       cmake \
       make \
       bash \
       gcc \
       g++ \
       icu-dev \
       supervisor \
       rabbitmq-c \
       rabbitmq-c-dev \
       libxslt \
       libxslt-dev \
       openssh \
       musl-dev \
       libintl \
       gettext-dev \
       git \
    && docker-php-ext-install -j$(nproc) \
        opcache \
        intl \
        pdo_mysql \
        bcmath \
        sockets \
        xsl \
        pcntl \
    && docker-php-ext-enable \
        opcache

# Install locales
ENV MUSL_LOCPATH /usr/share/i18n/locales/musl
RUN wget https://gitlab.com/rilian-la-te/musl-locales/-/archive/master/musl-locales-master.zip \
    && unzip musl-locales-master.zip \
      && cd musl-locales-master \
      && cmake -DLOCALE_PROFILE=OFF -D CMAKE_INSTALL_PREFIX:PATH=/usr . \
      && make \
      && make install \
      && cd .. \
      && rm -r musl-locales-master

# Copy PHP configuration

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV PATH="/root/.composer/vendor/bin:${PATH}"

RUN apk --update upgrade \
    && apk add --no-cache \
       nano \
       zsh \
       git \
       apache-ant \
    && pecl install xdebug-2.9.5

RUN composer global config minimum-stability dev \
    && composer global require friendsofphp/php-cs-fixer:"^3" \
    && composer global require --update-no-dev \
        edgedesign/phpqa \
        sebastian/phpcpd:"~5.0" \
        phpstan/phpstan:"0.12.81" nette/neon \
        php-parallel-lint/php-parallel-lint \
        php-parallel-lint/php-console-highlighter \
    && composer global update

COPY . /app

COPY etc/docker/dev/php/conf.d/php.ini /usr/local/etc/php/php.ini
COPY etc/docker/dev/php/conf.d/apcu.ini /usr/local/etc/php/conf.d/apcu.ini
COPY etc/docker/dev/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY etc/docker/dev/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Supervisor config of project
COPY etc/docker/dev/supervisord/supervisord.conf /etc/supervisord.conf

WORKDIR /app
